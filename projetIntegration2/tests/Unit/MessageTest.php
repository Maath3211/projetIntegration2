<?php

namespace Tests\Unit;

use App\Models\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class MessageTest extends TestCase
{
    use DatabaseTransactions; // Garde les changements uniquement dans le test, sans reset toute la base

    #[Test]
    public function un_message_peut_être_créé()
    {
        $message = Message::factory()->create([
            'message' => 'Salut, ça va ?',
        ]);

        $this->assertDatabaseHas('conversation_ami', [
            'message' => 'Salut, ça va ?',
        ]);
    }

    #[Test]
    public function un_message_peut_etre_retrouve_depuis_la_base()
    {
        $message = Message::factory()->create([
            'message' => 'Test de récupération',
        ]);

        $this->assertNotNull(Message::find($message->id));
    }



    //A changer 
    #[Test]
    public function un_message_appartient_a_un_utilisateur()
    {
        $utilisateur = \App\Models\User::factory()->create();
        $message = Message::factory()->create([
            'idEnvoyer' => $utilisateur->id,
        ]);

        $this->assertEquals($utilisateur->id, $message->from->id);
    }




    #[Test]
    public function un_message_peut_etre_envoye_avec_un_fichier_seulement()
    {
        Storage::fake('public');
    
        $fichier = UploadedFile::fake()->create('document.pdf', 100);
    
        $message = Message::factory()->create([
            'message' => null,
            'fichier' => $fichier->store('messages', 'public'),
        ]);
    
        $this->assertNull($message->message);
        $this->assertNotNull($message->fichier);
        Storage::disk('public')->assertExists($message->fichier);
    }

    #[Test]
    public function un_message_peut_etre_envoye_avec_un_message_seulement()
    {
        $message = Message::factory()->create([
            'message' => 'Ceci est un message sans fichier',
            'fichier' => null,
        ]);

        $this->assertNotNull($message->message);
        $this->assertNull($message->fichier);
    }

    #[Test]
    public function un_message_peut_etre_envoye_avec_texte_et_fichier()
    {
        Storage::fake('public');

        $fichier = UploadedFile::fake()->create('image.png', 150);

        $message = Message::factory()->create([
            'message' => 'Voici une image',
            'fichier' => $fichier->store('messages', 'public'),
        ]);

        $this->assertNotNull($message->message);
        $this->assertNotNull($message->fichier);
        Storage::disk('public')->assertExists($message->fichier);
    }



    #[Test]
    public function un_message_doit_avoir_un_message_ou_un_fichier()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
    
        $messageData = [
            'message' => null,
            'fichier' => null,
        ];
    
        // Appliquez les règles de validation manuellement
        $validator = \Validator::make($messageData, [
            'message' => 'required_without:fichier',
            'fichier' => 'required_without:message',
        ]);
    
        // Si la validation échoue, une exception ValidationException sera levée
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    
        // Créez le message uniquement si la validation passe
        Message::create($messageData);
    }
    




    #[Test]
    public function un_message_ne_peut_pas_etre_cree_sans_idEnvoyer()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Message::factory()->create([
            'idEnvoyer' => null,
        ]);
    }

    #[Test]
    public function un_message_ne_peut_pas_etre_cree_sans_idReceveur()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Message::factory()->create([
            'idReceveur' => null,
        ]);
    }



    #[Test]
    public function les_messages_sont_ordonnes_par_date_de_creation()
    {
        $message1 = Message::factory()->create(['created_at' => now()->subMinutes(5)]);
        $message2 = Message::factory()->create(['created_at' => now()]);

        $messages = Message::orderByDesc('created_at')->get();

        $this->assertEquals($message2->id, $messages->first()->id);
    }

    
    #[Test]
    public function un_message_peut_etre_supprime()
    {
        $message = Message::factory()->create();

        $message->delete();

        $this->assertDatabaseMissing('conversation_ami', ['id' => $message->id]);
    }

    #[Test]
    public function recuperer_les_messages_pour_un_utilisateur()
    {
        $receveur = \App\Models\User::factory()->create();
        Message::factory()->count(2)->create(['idReceveur' => $receveur->id]);
        Message::factory()->count(3)->create(); // Messages pour d'autres utilisateurs

        $messages = Message::where('idReceveur', $receveur->id)->get();

        $this->assertCount(2, $messages);
    }

    #[Test]
    public function un_message_peut_etre_modifie()
    {
        $message = Message::factory()->create([
            'message' => 'Message original',
        ]);
    
        $message->update(['message' => 'Message modifié']);
    
        $this->assertDatabaseHas('conversation_ami', [
            'id' => $message->id,
            'message' => 'Message modifié',
        ]);
    
        $this->assertDatabaseMissing('conversation_ami', [
            'id' => $message->id,
            'message' => 'Message original',
        ]);
    }

    #[Test]
    public function un_fichier_peut_etre_ajoute_a_un_message()
    {
        Storage::fake('public');

        $fichier = UploadedFile::fake()->create('document.pdf', 100);

        $message = Message::factory()->create([
            'fichier' => $fichier->store('messages', 'public'),
        ]);

        Storage::disk('public')->assertExists($message->fichier);
    }

    #[Test]
    public function un_fichier_peut_etre_modifie_dans_un_message()
    {
        Storage::fake('public');

        $ancienFichier = UploadedFile::fake()->create('ancien.pdf', 100);
        $nouveauFichier = UploadedFile::fake()->create('nouveau.pdf', 150);

        $message = Message::factory()->create([
            'fichier' => $ancienFichier->store('messages', 'public'),
        ]);

        Storage::disk('public')->assertExists($message->fichier);

        $message->update([
            'fichier' => $nouveauFichier->store('messages', 'public'),
        ]);

        Storage::disk('public')->assertMissing($ancienFichier->hashName());
        Storage::disk('public')->assertExists($message->fichier);
    }

    #[Test]
    public function un_fichier_peut_etre_supprime_dun_message()
    {
        Storage::fake('public');

        $fichier = UploadedFile::fake()->create('fichier.pdf', 100);

        $message = Message::factory()->create([
            'fichier' => $fichier->store('messages', 'public'),
        ]);

        Storage::disk('public')->assertExists($message->fichier);

        $message->update(['fichier' => null]);

        Storage::disk('public')->assertMissing($fichier->hashName());
    }








}
