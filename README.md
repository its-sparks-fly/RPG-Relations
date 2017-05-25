# RPG-Relations<br />
<h1>RPG-Relations</h1>
Mit diesem Plugin habt ihr und eure User die Möglichkeit, andere Charaktere in eure Profile aufzunehmen. Sie können erst in die Kategorie "Positiv", "Neutral" und "Negativ" aufgenommen und dann noch einmal mit individuellen Bezeichnungen versehen werden. Beziehungen beruhen nicht automatisch auf Gegenseitigkeit, sondern müssen von beiden Seiten eingetragen werden. 

<ul>
<li> Anzeigen anderer Charaktere auf dem eigenen Profil
<li> Unterteilt in "Positiv", "Neutral" und "Negativ"
<li> Angabe individueller Bezeichnungen (Ausgabe alphabetisch)
<li> PN-Benachrichtigung bei neuer Beziehung (wenn eingeschaltet)
<li> Sowohl der Eingetragene als auch der Eintragende können die Beziehungen wieder löschen
</ul>

<h1>Plugin funktionsfähig machen</h1>
<ul>
<li>Die Plugin-Datei ladet ihr in den angegebenen Ordner <b>inc/plugins</b> hoch.
<li>Das Plugin muss nun im Admin CP unter [b]Konfiguration - Plugins[/b] installiert und aktiviert werden
</ul><br />

Das Plugin ist nun einsatzbereit. Solltet ihr schon einiges an eurem Forum gemacht haben, und nicht wie ich im Testdurchlauf ein Default-Theme verwenden, kann es sein, dass nicht alle Variablen eingefügt werden. Sollte euch eine Anzeige fehlen, könnt ihr auf folgende Variablen zurückgreifen:

<blockquote>{$add_relation}  //  Formular, um Beziehungen einzutragen
* ruft member_profile_rprelations_add auf

{$show_relation}  // Übersicht der Beziehungen
* ruft member_profile_rprelations auf</blockquote>

<h1>Template-Änderungen<h1>
Folgende Templates werden durch dieses Plugin <i>neu hinzugefügt</i>:
<ul>
<li>member_profile_rprelations
<li>member_profile_rprelations_add
<li>member_profile_rprelations_bit
<li>member_profile_rprelations_none
<li>member_profile_rprelations_types
</ul>

Folgende Templates werden durch dieses Plugin <i>bearbeitet</i>:
<ul>
<li>member_profile
</ul>

<h1>Demo</h1>

<center>

<img src="http://fs5.directupload.net/images/170107/4yunaok6.png" /><br />
http://fs5.directupload.net/images/170107/4yunaok6.png<br /><br />

<img src="http://fs5.directupload.net/images/170107/r72c3wk5.png" /><br />
http://fs5.directupload.net/images/170107/r72c3wk5.png<br /><br />

</center>
