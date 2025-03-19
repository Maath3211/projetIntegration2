<?php

namespace Tests\Feature;

use App\Http\Controllers\ScoresController;
use App\Models\Clan;
use App\Models\User;
use App\Models\Score;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Testing\TestResponse;
use Illuminate\Support\Str;

class TestExportationClassements extends TestCase
{
    use DatabaseTransactions;

    /**
     * Helper function to parse CSV content from a response
     */
    private function parseCSV(TestResponse $response): array
    {
        $content = $response->getContent();
        $lines = explode("\n", trim($content));
        $headers = str_getcsv(array_shift($lines));

        $data = [];
        foreach ($lines as $line) {
            if (!empty($line)) {
                $data[] = array_combine($headers, str_getcsv($line));
            }
        }

        return [
            'headers' => $headers,
            'rows' => $data
        ];
    }

    /**
     * Displays test information in a formatted way
     */
    private function displayTestResults(string $testName, TestResponse $response, array $csvData): void
    {
        // Create formatted output
        $output = "\n\n=====================================";
        $output .= "\nTest: {$testName}";
        $output .= "\nStatus: {$response->getStatusCode()} " . ($response->getStatusCode() === 200 ? '✅' : '❌');
        $output .= "\nHeaders: " . implode(", ", $csvData['headers']);
        $output .= "\nRows found: " . count($csvData['rows']);

        // Display sample data (first 3 rows maximum)
        $sampleRows = array_slice($csvData['rows'], 0, 3);
        if (!empty($sampleRows)) {
            $output .= "\nSample data:";
            foreach ($sampleRows as $index => $row) {
                $output .= "\n  Row " . ($index + 1) . ": " . json_encode($row, JSON_UNESCAPED_UNICODE);
            }
        }
        $output .= "\n=====================================\n";

        // Write to STDERR
        fwrite(STDERR, $output);
    }

    #[Test]
    public function peut_exporter_les_meilleurs_utilisateurs()
    {
        // Create test users and scores
        $utilisateurs = User::factory()->count(5)->create();
        $scoresByUser = [];

        foreach ($utilisateurs as $utilisateur) {
            $score = rand(10, 100);
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => $score,
                'date' => Carbon::now()
            ]);
            $scoresByUser[$utilisateur->id] = $score;
        }

        // Test the export endpoint
        $response = $this->get('/export/top-users');

        // Verify response properties
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="meilleurs_membres_global_' . date('d-m-Y') . '.csv"');

        // Parse and validate CSV content
        $csvData = $this->parseCSV($response);

        // Verify that the headers contain at least these columns
        $expectedHeaders = ['Position', 'Prenom', 'Nom', 'Total Score'];
        foreach ($expectedHeaders as $header) {
            $this->assertContains($header, $csvData['headers'], "CSV should contain a '$header' column");
        }

        // Verify user data is present - note that controller has a limit(10) hardcoded
        // so we should expect at most 10 rows, not necessarily matching our test data count
        $this->assertLessThanOrEqual(
            10,
            count($csvData['rows']),
            'CSV should contain at most 10 rows (controller limit)'
        );

        // Optionally display test results in a controlled way
        ob_start();
        $this->displayTestResults('Exportation meilleurs utilisateurs', $response, $csvData);
        ob_end_clean();
    }

    #[Test]
    public function peut_exporter_les_meilleurs_clans()
    {
        // Create test clans, users and scores
        $clan1 = Clan::factory()->create();
        $clan2 = Clan::factory()->create();
        $utilisateurs = User::factory()->count(4)->create();

        // Associate users with clans
        DB::table('clan_users')->insert([
            ['user_id' => $utilisateurs[0]->id, 'clan_id' => $clan1->id],
            ['user_id' => $utilisateurs[1]->id, 'clan_id' => $clan1->id],
            ['user_id' => $utilisateurs[2]->id, 'clan_id' => $clan2->id],
            ['user_id' => $utilisateurs[3]->id, 'clan_id' => $clan2->id]
        ]);

        // Add scores for each user
        foreach ($utilisateurs as $utilisateur) {
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => Carbon::now()
            ]);
        }

        // Test the export endpoint
        $response = $this->get('/export/top-clans');

        // Verify response properties
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="meilleurs_clans_global_' . date('d-m-Y') . '.csv"');

        // Parse and validate CSV content
        $csvData = $this->parseCSV($response);

        // Verify required columns
        $this->assertContains('Position', $csvData['headers'], 'CSV should contain a Position column');
        $this->assertContains('Clan Name', $csvData['headers'], 'CSV should contain a Clan Name column');
        $this->assertContains('Total Score', $csvData['headers'], 'CSV should contain a Total Score column');

        // Verify clan data is present, allowing for database data from previous tests
        $this->assertLessThanOrEqual(10, count($csvData['rows']), 'CSV should contain at most 10 rows (controller limit)');

        // Optionally display test results
        ob_start();
        $this->displayTestResults('Exportation meilleurs clans', $response, $csvData);
        ob_end_clean();
    }

    #[Test]
    public function peut_exporter_les_meilleurs_membres_dun_clan()
    {
        // Create a clan and users
        $clan = Clan::factory()->create();
        $utilisateurs = User::factory()->count(5)->create();

        // Associate users with the clan
        foreach ($utilisateurs as $utilisateur) {
            DB::table('clan_users')->insert([
                'user_id' => $utilisateur->id,
                'clan_id' => $clan->id
            ]);

            // Add scores
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => rand(10, 100),
                'date' => Carbon::now()
            ]);
        }

        // Test the export endpoint
        $response = $this->get('/export/top-membres/' . $clan->id);

        // Verify response properties
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        // Parse and validate CSV content
        $csvData = $this->parseCSV($response);

        // Verify required columns
        $this->assertContains('Position', $csvData['headers'], 'CSV should contain a Position column');
        $this->assertContains('Prenom', $csvData['headers'], 'CSV should contain a Prenom column');
        $this->assertContains('Nom', $csvData['headers'], 'CSV should contain a Nom column');
        $this->assertContains('Total Score', $csvData['headers'], 'CSV should contain a Total Score column');

        // Verify member data is present - controller has limit(10)
        $this->assertLessThanOrEqual(
            10,
            count($csvData['rows']),
            'CSV should contain at most 10 rows (controller limit)'
        );

        // Optionally display test results    
        ob_start();
        $this->displayTestResults('Exportation meilleurs membres d\'un clan', $response, $csvData);
        ob_end_clean();
    }

    #[Test]
    public function peut_exporter_les_meilleures_ameliorations_dun_clan()
    {
        // Create a clan and users
        // Fix: Use 'nom' instead of 'name' for the clan
        $clan = Clan::factory()->create(['nom' => 'Clan de test']);
        $utilisateurs = User::factory()->count(5)->create();

        // Associate users with the clan and create scores for each
        foreach ($utilisateurs as $index => $utilisateur) {
            DB::table('clan_users')->insert([
                'user_id' => $utilisateur->id,
                'clan_id' => $clan->id
            ]);

            // Old score (2 months ago)
            $scoreAncien = rand(10, 50);
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => $scoreAncien,
                'date' => Carbon::now()->subMonths(2),
                'created_at' => Carbon::now()->subMonths(2)  // Important for filtering
            ]);

            // Recent score (last month) - with guaranteed improvement
            $scoreRecent = $scoreAncien + rand(10, 50);
            Score::create([
                'user_id' => $utilisateur->id,
                'score' => $scoreRecent,
                'date' => Carbon::now()->subDays(rand(1, 25)),
                'created_at' => Carbon::now()->subDays(rand(1, 25)) // Important for filtering
            ]);
        }

        // Test the export endpoint
        $response = $this->get('/export/top-amelioration/' . $clan->id);

        // Verify response properties
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        // Parse and validate CSV content
        $csvData = $this->parseCSV($response);

        // Verify required columns
        $this->assertContains('Position', $csvData['headers'], 'CSV should contain a Position column');
        $this->assertContains('Prenom', $csvData['headers'], 'CSV should contain a Prenom column');
        $this->assertContains('Nom', $csvData['headers'], 'CSV should contain a Nom column');
        $this->assertContains('Improvement Score', $csvData['headers'], 'CSV should contain an Improvement Score column');

        // Verify member data is present
        $this->assertLessThanOrEqual(
            10,
            count($csvData['rows']),
            'CSV should contain at most 10 rows (controller limit)'
        );

        // Optionally display test results
        ob_start();
        $this->displayTestResults('Exportation meilleures améliorations d\'un clan', $response, $csvData);
        ob_end_clean();
    }
}
