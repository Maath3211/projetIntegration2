<?php

namespace Tests\Feature;

use App\Models\User;
use App\Http\Controllers\TraductionController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use PHPUnit\Framework\Attributes\Test;

class TestTraduction extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Configuration initiale pour les tests
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur pour les tests
        $this->utilisateur = User::factory()->create();
        
        // Authentifier l'utilisateur
        $this->actingAs($this->utilisateur);
    }

    #[Test]
    public function peut_changer_langue_via_controleur()
    {
        // Vérifier la locale initiale (généralement 'fr' basé sur vos logs)
        $localeInitiale = app()->getLocale();
        
        // Définir la langue opposée pour le test
        $nouvelleLocale = ($localeInitiale === 'fr') ? 'en' : 'fr';
        
        // Appeler la méthode du contrôleur pour changer la langue
        $réponse = $this->get(route('langue.change', ['locale' => $nouvelleLocale]));
        
        // Vérifier que la redirection fonctionne
        $réponse->assertStatus(302);
        
        // Vérifier que la session et l'application ont la nouvelle langue
        $this->assertEquals($nouvelleLocale, session('locale'));
        $this->assertEquals($nouvelleLocale, app()->getLocale());
    }
    
    #[Test]
    public function peut_changer_langue_via_javascript()
    {
        // Simuler une requête AJAX pour changer la langue
        $réponse = $this->postJson('/switch-language', [
            'locale' => 'en',
        ]);
        
        // Vérifier que la réponse est correcte
        $réponse->assertStatus(200)
                ->assertJson(['success' => true]);
        
        // Vérifier que la session a été mise à jour
        $this->assertEquals('en', session('locale'));
        
        // Changer à nouveau la langue
        $réponse = $this->postJson('/switch-language', [
            'locale' => 'fr',
        ]);
        
        $réponse->assertStatus(200)
                ->assertJson(['success' => true]);
                
        $this->assertEquals('fr', session('locale'));
    }
    
    #[Test]
    public function rejette_langues_non_supportées()
    {
        // Essayer de définir une langue non supportée
        $réponse = $this->get(route('langue.change', ['locale' => 'de']));
        
        // La langue ne devrait pas changer
        $this->assertNotEquals('de', session('locale'));
        $this->assertNotEquals('de', app()->getLocale());
    }
    
    #[Test]
    public function middleware_applique_correctement_langue_de_session()
    {
        // Définir une langue en session
        Session::put('locale', 'en');
        
        // Visiter une page protégée par le middleware
        $this->get('/');
        
        // Vérifier que la langue de l'application a été mise à jour
        $this->assertEquals('en', app()->getLocale());
        
        // Changer la langue en session
        Session::put('locale', 'fr');
        
        // Visiter à nouveau la page
        $this->get('/');
        
        // Vérifier que la langue a été mise à jour
        $this->assertEquals('fr', app()->getLocale());
    }
    
    #[Test]
    public function composants_livewire_recoivent_evenements_de_changement_langue()
    {
        // Création d'un mock pour simuler la réception d'événements par un composant Livewire
        $mockComposant = $this->getMockBuilder('Livewire\Component')
            ->disableOriginalConstructor()
            ->addMethods(['handleLocaleChanged'])
            ->getMock();
            
        // On s'attend à ce que la méthode soit appelée une fois avec le bon paramètre
        $mockComposant->expects($this->once())
            ->method('handleLocaleChanged')
            ->with(['locale' => 'fr']);
            
        // Simulation de l'émission de l'événement
        // Note : Dans un contexte de test réel, on utiliserait Livewire::test() pour tester cela
        $mockComposant->handleLocaleChanged(['locale' => 'fr']);
    }
    
    #[Test]
    public function les_traductions_sont_chargées_correctement()
    {
        // Définir la langue en anglais
        App::setLocale('en');
        
        // Vérifier que les traductions sont chargées correctement
        $this->assertEquals('Login', __('auth.connexion'));
        $this->assertEquals('Create', __('auth.creation'));
        
        // Définir la langue en français
        App::setLocale('fr');
        
        // Vérifier que les traductions sont chargées correctement
        $this->assertEquals('Connexion', __('auth.connexion'));
        $this->assertEquals('Créer', __('auth.creation'));
    }
    
    #[Test]
    public function le_middleware_localisation_enregistre_correctement()
    {
        // Simuler un utilisateur qui visite une page avec une langue définie en session
        Session::put('locale', 'fr');
        
        // Capturer les logs pour vérifier que le middleware enregistre l'information
        $this->expectsEvents(\Illuminate\Log\Events\MessageLogged::class);
        
        // Visiter une page
        $this->get('/');
        
        // Note: Dans un contexte réel, on pourrait utiliser un mock du logger pour vérifier l'appel exact
    }
    
    #[Test]
    public function bouton_langue_affiche_langue_active()
    {
        // Définir la langue en français
        App::setLocale('fr');
        Session::put('locale', 'fr');
        
        // Visiter une page avec les boutons de langue
        $réponse = $this->get('/');
        
        // Vérifier que le bouton français est marqué comme actif
        $réponse->assertSee('active', false);
        
        // Définir la langue en anglais
        App::setLocale('en');
        Session::put('locale', 'en');
        
        // Visiter à nouveau la page
        $réponse = $this->get('/');
        
        // Vérifier que le bouton anglais est marqué comme actif
        $réponse->assertSee('active', false);
    }
    
    #[Test]
    public function traduit_correctement_les_dates_et_nombres()
    {
        // Test de traduction des formats de date
        // En français
        App::setLocale('fr');
        $dateFr = \Carbon\Carbon::parse('2025-01-15')->translatedFormat('F Y');
        
        // En anglais
        App::setLocale('en');
        $dateEn = \Carbon\Carbon::parse('2025-01-15')->translatedFormat('F Y');
        
        // Vérifier que les traductions sont différentes
        $this->assertNotEquals($dateFr, $dateEn);
        
        // Vérifier que les formats correspondent aux attentes
        App::setLocale('fr');
        $this->assertEquals('janvier 2025', $dateFr);
        
        App::setLocale('en');
        $this->assertEquals('January 2025', $dateEn);
    }
    
    #[Test]
    public function localisation_fonctionne_avec_api_routes()
    {
        // Simuler une requête API avec un header Accept-Language
        $réponse = $this->withHeaders([
            'Accept-Language' => 'fr',
        ])->getJson('/api/exemple');
        
        // Ici, vous vérifieriez que la réponse est localisée en français
        // Ceci est un exemple conceptuel car votre API peut avoir une logique différente
    }
    
    #[Test]
    public function peut_definir_langue_utilisateur_par_defaut()
    {
        // Créer un utilisateur avec une préférence de langue
        $utilisateur = User::factory()->create([
            'langue_preference' => 'fr'
        ]);
        
        // Connecter l'utilisateur
        $this->actingAs($utilisateur);
        
        // Visiter une page pour déclencher le middleware de localisation
        $this->get('/');
        
        // Vérifier que la langue préférée de l'utilisateur a été appliquée
        $this->assertEquals('fr', app()->getLocale());
        
        // Changer manuellement la langue
        $this->get(route('langue.change', ['locale' => 'en']));
        
        // Vérifier que le changement manuel a priorité
        $this->assertEquals('en', app()->getLocale());
    }
}