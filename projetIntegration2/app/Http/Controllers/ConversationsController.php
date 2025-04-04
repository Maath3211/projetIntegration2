<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clan;
use App\Models\User;
use App\Models\UtilisateurClan;
use App\Models\Message;
use App\Models\Canal;

use App\Repository\ConversationsRepository;
use App\Repository\ConversationsClan;
use App\Http\Requests\StoreMessage;
use App\Events\PusherBroadcast;
use App\Events\MessageGroup;
use App\Events\SuppressionMessageGroup;
use App\Events\SuppressionMessageAmis;






class ConversationsController extends Controller
{

    private $ConvRepository;
    private $ClanRepository;

    public function __construct(
        ConversationsRepository $conversationRepository,
        ConversationsClan $ClanRepository
    ) {
        $this->ConvRepository = $conversationRepository;
        $this->ClanRepository = $ClanRepository;
    }


    public function index()
    {

        $utilisateur = auth()->id();
        $utilisateur = User::findOrFail($utilisateur);

        if (!$utilisateur){
            Log::info('Utilisateur pas connecté.');
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour afficher les clans.');
        }

        $clans = $utilisateur->clans()->get();

        $userId = auth()->id();
        $users = \DB::table('demande_amis')
            ->join('users', function ($join) use ($userId) {
                $join->on('users.id', '=', 'demande_amis.requested_id')
                    ->orOn('users.id', '=', 'demande_amis.requester_id');
            })
            ->select('users.email', 'users.id')
            ->where('demande_amis.status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('demande_amis.requester_id', $userId)
                    ->orWhere('demande_amis.requested_id', $userId);
            })
            ->where('users.id', '!=', $userId)
            ->get();




        return view('conversations.index', [
            'users' => $users,
            'clans' => $clans,
        ]);
    }

    public function show(User $user)
    {
        $utilisateur = auth()->id();
        $utilisateur = User::findOrFail($utilisateur);

        if (!$utilisateur) {
            Log::info('Utilisateur pas connecté.');
            return redirect('/connexion')->with('erreur', 'Vous devez être connectés pour afficher les clans.');
        }

        $clans = $utilisateur->clans()->get();

        $userId = auth()->id();
        $users = \DB::table('demande_amis')
            ->join('users', function ($join) use ($userId) {
                $join->on('users.id', '=', 'demande_amis.requested_id')
                    ->orOn('users.id', '=', 'demande_amis.requester_id');
            })
            ->select('users.email', 'users.id')
            ->where('demande_amis.status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('demande_amis.requester_id', $userId)
                    ->orWhere('demande_amis.requested_id', $userId);
            })
            ->where('users.id', '!=', $userId)
            ->get();

        // Vérifier si l'utilisateur est ami
        //nom des tables son en englais
        // Je n'ai pas créé cette table
        $isFriend = \DB::table('demande_amis')
            ->where(function ($query) use ($userId, $user) {
                $query->where('requester_id', $userId)
                    ->where('requested_id', $user->id);
            })
            ->orWhere(function ($query) use ($userId, $user) {
                $query->where('requester_id', $user->id)
                    ->where('requested_id', $userId);
            })
            ->where('status', 'accepted')
            ->exists();

        if (!$isFriend) {
            //\Log::info('Tentative d\'accès à une conversation sans être ami.', ['user_id' => $userId, 'target_id' => $user->id]);
            return redirect()->route('conversations.index')->with('erreur', 'Vous devez être ami pour accéder à cette conversation.');
        }

        $messages = $this->ConvRepository->getMessageFor(auth()->id(), $user->id);

        return view('conversations.show', [
            'users' => $users,
            'user' => $user,
            'messages' => $messages,
            'clans' => $clans,
        ]);
    }

    //Pour utilisation du pusher nom de variable en englais
    public function broadcast(Request $request)
    {
        ////\Log::info('Début de la diffusion du message', ['user_id' => auth()->id()]);

        $request->validate([
            'message' => 'nullable|string|max:2000',
            'fichier' => 'nullable|file|max:20480', // 20 Mo
        ]);

        if (!$request->filled('message') && !$request->hasFile('fichier')) {
            //\Log::warning('Aucun message ou fichier fourni', ['user_id' => auth()->id()]);
            return response()->json(['error' => 'Vous devez envoyer soit un message, soit un fichier, soit les deux.'], 422);
        }

        try {
            $fichierNom = null;
            if ($request->hasFile('fichier')) {
                $fichier = $request->file('fichier');

                // Générer un nom unique avec horodatage
                $fichierNom = time() . '_' . $fichier->getClientOriginalName();

                // Déterminer le dossier en fonction du type de fichier
                $dossier = in_array($fichier->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])
                    ? 'img/conversations_photo/'
                    : 'fichier/conversations_fichier/';

                // Stocker le fichier
                $fichier->move(public_path($dossier), $fichierNom);
                ////\Log::info('Fichier téléchargé avec succès', ['fichier_nom' => $fichierNom, 'dossier' => $dossier]);
            }

            // Insérer le message dans la base de données en utilisant le modèle Message
            $message = Message::create([
                'idEnvoyer' => auth()->id(),
                'idReceveur' => $request->vers,
                'message' => $request->message,
                'fichier' => $fichierNom, // Stocke le chemin public
                'created_at' => now(),
            ]);

            ////\Log::info('Message créé avec succès', ['message_id' => $message->id]);

            // Diffuser l’événement via Pusher
            //Obliger en englais
            broadcast(new PusherBroadcast(e($request->message), auth()->id(), $request->vers, false, $message->id, $fichierNom, auth()->user()->email))
                ->toOthers();

            ////\Log::info('Message diffusé avec succès', ['message_id' => $message->id]);

        } catch (\Exception $e) {
            ////\Log::error('❌ Erreur lors du broadcast: ' . $e->getMessage());
        }

        return response()->json([
            'message' => $request->message,
            'dernier_id' => $message->id,
            'idEnvoyer' => auth()->id(),
            'envoyerEmail' => auth()->user()->email,
            'fichier' => $fichierNom ? asset($dossier . $fichierNom) : null, // Retourne l'URL complète
            'email' => auth()->user()->email,
        ]);
    }



    //Pour utilisation du pusher nom de variable en englais
    public function receive(Request $request){
        //\Log::info('❌ Erreur lors du broadcast: ' . $request);
    return response()->json([
        'message' => $request->message,
        'idEnvoyer' => $request->sender_id,
        'idReceveur' => $request->receiver_id,
        'supprimer' => $request->deleted,
        'dernier_id' => $request->last_id,
        'photo' => $request->photo,
        'email' => $request->email,

    ]);
    }

    public function destroy(Message $message)
    {
        ////\Log::info('Tentative de suppression du message', ['message_id' => $message->id, 'user_id' => auth()->id()]);

        if (auth()->id() !== $message->idEnvoyer) {
            //\Log::warning('Action non autorisée pour la suppression du message', ['message_id' => $message->id, 'user_id' => auth()->id()]);
            return response()->json(['error' => 'Action non autorisée'], 403);
        }

        ////\Log::info('Détails du message avant suppression', ['message_id' => $message->id, 'fichier' => $message->fichier]);

        if ($message->fichier) {
            $fichierNom = $message->fichier;

            // Déterminer le dossier selon l'extension
            $dossier = in_array(pathinfo($fichierNom, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])
                ? 'img/conversations_photo/'
                : 'fichier/conversations_fichier/';

            $fichierPath = public_path($dossier . $fichierNom);

            ////\Log::info('Chemin du fichier à supprimer', ['fichier_path' => $fichierPath]);

            if (file_exists($fichierPath)) {
                unlink($fichierPath);
                ////\Log::info('Fichier supprimé', ['fichier_path' => $fichierPath]);
            } else {
                ////\Log::warning('Le fichier n\'existe pas', ['fichier_path' => $fichierPath]);
            }
        } else {
            ////\Log::info('Aucun fichier associé au message', ['message_id' => $message->id]);
        }

        $messageId = $message->id;
        $message->delete();

        ////\Log::info('Message supprimé avec succès', ['message_id' => $messageId]);

        broadcast(new SuppressionMessageAmis($messageId, $message->idEnvoyer, $message->idReceveur))->toOthers();

        return response()->json(['success' => 'Message supprimé']);
    }




    //Modification pour avoir mes points

    public function showModificationMessage(){
        $messagesClan = \DB::table('conversation_clan')
            ->select('id', 'message', 'created_at', 'fichier')
            ->where('idEnvoyer', auth()->id())
            ->get();

        $messagesAmi = \DB::table('conversation_ami')
            ->select('id', 'message', 'created_at', 'fichier')
            ->where('idEnvoyer', auth()->id())
            ->get();

        return view('conversations.modification',[
            'messages' => $messagesClan,
            'messagesAmi' => $messagesAmi,
        ]);
    }

    public function updateMessage(Request $request, $id)
    {
        $request->validate([
            'nouveau_message' => 'required|string',
        ]);

        $message = UtilisateurClan::findOrFail($id);

        if (auth()->id() !== $message->idEnvoyer) {
            return response()->json(['error' => 'Action non autorisée'], 403);
        }

        $message->message = $request->nouveau_message;
        $message->save();

        return redirect()->route('conversations.showModificationMessage');
    }


    public function updateMessageAmi(Request $request, $id)
    {
        $request->validate([
            'nouveau_message' => 'required|string',
        ]);

        $message = Message::findOrFail($id);

        if (auth()->id() !== $message->idEnvoyer) {
            return response()->json(['error' => 'Action non autorisée'], 403);
        }

        $message->message = $request->nouveau_message;
        $message->save();

        return redirect()->route('conversations.showModificationMessage');
    }

}
