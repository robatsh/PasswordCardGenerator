<?php
namespace App;

class PasswordCardGenerator {
    private array $upperLetters;
    private array $lowerLetters;
    private array $numbers = ['0','1','2','3','4','5','6','7','8','9'];
    private array $symbols = ['*', '_', '-', '$', '#', '+', '.', '!', '@', '?', ',', ';'];

    public function __construct() {
        $this->upperLetters = $this->generateLetterRange('A', 'Z');
        $this->lowerLetters = $this->generateLetterRange('a', 'z');
    }

    private function generateLetterRange(string $start, string $end): array {
        $letters = [];
        for ($char = ord($start); $char <= ord($end); $char++) {
            $letters[] = chr($char);
        }
        return $letters;
    }

    public function getFullRange(): array {
        return array_merge($this->upperLetters, $this->lowerLetters, $this->numbers, $this->symbols);
    }

    public function getRandomSymbol(): string {
        $range = $this->getFullRange();
        return $range[random_int(0, count($range) - 1)];
    }

    /**
     * Generiert die HTML-Tabelle der Passwortkarte.
     *
     * @param int $rows Anzahl der Zeilen.
     * @return string HTML der Passwortkarte.
     */
    public function generateCard(int $rows = 10): string {
        $letters = $this->upperLetters;
        $numCols = count($letters);
        $content = '<table class="password-card-table">';
        
        // Obere Kopfzeile
        $content .= '<tr>';
        $content .= '<th class="corner"></th>';
        foreach ($letters as $letter) {
            $content .= '<th class="header-cell">' . $letter . '</th>';
        }
        $content .= '<th class="corner"></th>';
        $content .= '</tr>';
        
        // Datenzeilen
        for ($i = 0; $i < $rows; $i++) {
            $content .= '<tr>';
            $content .= '<th class="side-cell">' . $i . '</th>';
            for ($j = 0; $j < $numCols; $j++) {
                $content .= '<td class="card-cell">' . $this->getRandomSymbol() . '</td>';
            }
            $content .= '<th class="side-cell">' . $i . '</th>';
            $content .= '</tr>';
        }
        
        // Untere Kopfzeile
        $content .= '<tr>';
        $content .= '<th class="corner"></th>';
        foreach ($letters as $letter) {
            $content .= '<th class="header-cell">' . $letter . '</th>';
        }
        $content .= '<th class="corner"></th>';
        $content .= '</tr>';
        $content .= '</table>';
        
        $content .= '<div id="pswc_inst" class="instructions" style="text-align:left;font-size:9px;">
            <u></u><b>Bedienungsanleitung für die Passwortkarte</b></u>

<b>Was ist eine Passwortkarte?</b>
<p>Eine Passwortkarte ist eine zufällig generierte Tabelle mit Buchstaben, Zahlen und Sonderzeichen. Damit kannst du sichere Passwörter erstellen, ohne sie dir direkt merken zu müssen. Stattdessen nutzt du ein persönliches System, um dein Passwort aus der Karte abzulesen.</p>

<b>Warum eine Passwortkarte nutzen?</b>
<ul>
<li><strong>Sicher:</strong> Niemand kann dein Passwort erraten, wenn er die Karte nicht hat.</li>
<li><strong>Flexibel:</strong> Die Anzahl der Zeilen und Spalten kann angepasst werden.</li>
<li><strong>Einfach:</strong> Du musst dir nur dein System merken, nicht das Passwort selbst.</li>
<li><strong>Offline:</strong> Kein Speichern von Passwörtern auf unsicheren Geräten nötig.</li>
</ul>

<b>So funktioniert es</b>
<ol>
<li><strong>Erstelle deine persönliche Passwortkarte.</strong>
<ul>
<li>Nutze den Generator, um eine neue Karte zu erstellen.</li>
<li>Wähle die Anzahl der Zeilen und Spalten nach deinen Bedürfnissen.</li>
<li>Drucke die Karte aus oder speichere sie sicher ab.</li>
</ul>
</li>
<li><strong>Lege dein persönliches System fest.</strong>
<ul>
<li>Wähle eine Startposition (z. B. „3. Reihe, 5. Spalte“).</li>
<li>Entscheide, in welche Richtung du dein Passwort liest (z. B. „5 Zeichen nach rechts“ oder „Diagonal nach unten“).</li>
<li>Notiere dein System an einem sicheren Ort, falls du es dir nicht merken kannst.</li>
</ul>
</li>
<li><strong>Erstelle dein Passwort.</strong>
<ul>
<li>Lies die Zeichen gemäß deinem System von der Karte ab.</li>
<li>Nutze das Passwort für deinen Passwort-Manager oder Online-Konten.</li>
</ul>
</li>
<li><strong>Backup anlegen!</strong>
<ul>
<li>Bewahre eine Kopie deiner Karte sicher auf (nicht im Portemonnaie!).</li>
<li>Falls die Karte verloren geht, kannst du ohne Backup das Passwort nicht wiederherstellen.</li>
</ul>
</li>
</ol>

<b>Beispiel</b>
<ul>
<li>Deine Startposition ist: <strong>Zeile 2, Spalte D</strong></li>
<li>Dein System: <strong>6 Zeichen nach rechts lesen</strong></li>
<li>Ergebnis (zum Beispiel): <strong>@BCQFs</strong></li>
</ul>
<p>Das bedeutet, dass dein Passwort immer sicher bleibt, aber du es mit der Karte einfach rekonstruieren kannst!</p>

<b>Wichtige Sicherheitstipps</b>
<ul>
<li>Speichere deine Passwortkarte nicht digital ohne Verschlüsselung.</li>
<li>Wähle ein System, das nur du verstehst.</li>
<li>Erstelle regelmäßig eine neue Karte zur erhöhten Sicherheit.</li>
<li>Falls die Karte verloren geht, ersetze sie sofort.</li>
</ul>

<p>Mit dieser Methode kannst du deine Passwörter sicher und einfach verwalten!</p>
        </div>';
        return $content;
    }
}
