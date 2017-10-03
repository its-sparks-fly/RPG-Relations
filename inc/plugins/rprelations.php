<?php

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.");
}

$plugins->add_hook("member_profile_end", "rprelations_member_profile_end");
$plugins->add_hook("usercp_do_options_end", "rprelations_usercp_options");
$plugins->add_hook("usercp_options_start", "rprelations_usercp");


function rprelations_info()
{
	return array(
		"name"			=> "RPG-Relations",
		"description"	=> "Charaktere können in Profilen anderer Charaktere samt Beziehungsangabe & Kategorisierung in Positiv, Negativ & Neutral angegeben werden.",
		"website"		=> "http://www.storming-gates.de",
		"author"		=> "sparks fly",
		"authorsite"	=> "http://www.storming-gates.de",
		"version"		=> "1.0",
		"compatibility" => "*"
	);
}

function rprelations_install()
{
  global $db, $mybb;

	// Tabelle erstellen
	$db->query("CREATE TABLE `".TABLE_PREFIX."rprelations` ( `rid` INT NOT NULL AUTO_INCREMENT , `suid` INT(11) NOT NULL , `ruid` INT(11) NOT NULL , `type` VARCHAR(155) NOT NULL , `relation` VARCHAR(155) NOT NULL , PRIMARY KEY (`rid`)) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;");

	$db->query("ALTER TABLE `".TABLE_PREFIX."users` ADD `rprelationspm` int(11) NOT NULL;");

	// Templates erstellen
	$insert_array = array(
		'title'		=> 'member_profile_rprelations',
		'template'	=> $db->escape_string('<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder tfixed">
<tr>
<td class="thead"><strong>Beziehungen</strong></td>
</tr>
<tr>
<td class="trow1" align="center">
	{$relation_type_bit}
</td>
</tr>
</table>
<br />'),
		'sid'		=> '-1',
		'version'	=> '',
		'dateline'	=> TIME_NOW
	);
	$db->insert_query("templates", $insert_array);

	$insert_array = array(
		'title'		=> 'member_profile_rprelations_add',
		'template'	=> $db->escape_string('<table border="0" cellspacing="{$theme[\'borderwidth\']}" cellpadding="{$theme[\'tablespace\']}" class="tborder tfixed">
<tr>
<td class="thead"><strong>Zur Beziehungskiste hinzufügen</strong></td>
</tr>
<tr>
<td class="trow1" align="center">
	<form method="post" action="member.php?action=profile&addrel={$memprofile[uid]}" id="addrel">

		<input type="text" class="textbox" name="relation" id="relation" value="{$relvalue}" />
		<select name="type" id="type">
			<option>Kategorie wählen</option>
			<option value="positiv">Positiv</option>
			<option value="neutral">Neutral</option>
			<option value="negativ">Negativ</option>
		</select>
		<input type="submit" name="submit" id="submit" class="button" />

	</form>
</td>
</tr>
</table>
<br />'),
		'sid'		=> '-1',
		'version'	=> '',
		'dateline'	=> TIME_NOW
	);
	$db->insert_query("templates", $insert_array);

	$insert_array = array(
		'title'		=> 'member_profile_rprelations_bit',
		'template'	=> $db->escape_string('<div class="trow2" style="text-align: center;">
	<table border="0" cellpadding="5" cellspacing="5" class="smalltext" style="width: 100%;">
		<tr>
			<td width="10%">{$useravatar}
			</td>
			<td>{$relation[\'profilelink\']} &raquo; {$relation[\'relation\']}
			</td>
			<td>
				{$delete_rel}
			</td>
		</tr>
	</table>
</div>'),
		'sid'		=> '-1',
		'version'	=> '',
		'dateline'	=> TIME_NOW
	);
	$db->insert_query("templates", $insert_array);

$insert_array = array(
	'title'		=> 'member_profile_rprelations_none',
	'template'	=> $db->escape_string('<div class="trow2" style="text-align: left;">
	<table border="0" cellpadding="5" cellspacing="5" class="smalltext">
		<tr>
			<td>Keine Einträge vorhanden!
			</td>
		</tr>
	</table>
</div>'),
	'sid'		=> '-1',
	'version'	=> '',
	'dateline'	=> TIME_NOW
);
$db->insert_query("templates", $insert_array);

$insert_array = array(
	'title'		=> 'member_profile_rprelations_types',
	'template'	=> $db->escape_string('<div style="width: 32.3%; float: left; margin: 5px;">
	<div class="tcat">{$type}</div>
	<div style="height: 150px; overflow: auto;">
		{$relation_none}
		{$relation_bit}
	</div>
</div>'),
	'sid'		=> '-1',
	'version'	=> '',
	'dateline'	=> TIME_NOW
);

$db->insert_query("templates", $insert_array);

$insert_array = array(
	'title'		=> 'usercp_options_rprelationspm',
	'template'	=> $db->escape_string('<tr>
<td valign="top" width="1"><input type="checkbox" class="checkbox" name="rprelationspm" id="rprelationspm" value="1" {$rprelationspmcheck} /></td>
<td><span class="smalltext"><label for="rprelationspm">PN-Benachrichtigung bei Beziehungskisten-Veränderungen?</label></span></td>
</tr>'),
	'sid'		=> '-1',
	'version'	=> '',
	'dateline'	=> TIME_NOW
);

$db->insert_query("templates", $insert_array);

}

function rprelations_is_installed()
{
  global $db, $mybb;

	if($db->table_exists("rprelations"))  {
	  return true;
	}
	  return false;
}

function rprelations_uninstall()
{
  global $db, $mybb;

	// Tabelle löschen
	$db->query("DROP TABLE `".TABLE_PREFIX."rprelations`");
	if($db->field_exists("rprelationspm", "users"))
	{
		$db->drop_column("users", "rprelationspm");
	}

	// Templates entfernen
  $db->delete_query("templates", "title IN('member_profile_rprelations', 'member_profile_rprelations_add', 'member_profile_rprelations_bit', 'member_profile_rprelations_none', 'member_profile_rprelations_types', 'usercp_options_rprelationspm')");
}

function rprelations_activate()
{
	global $db, $mybb;

	// PM-Feld einfügen
	if(!$db->field_exists("rprelationspm", "users"))
  {
    $db->query("ALTER TABLE `".TABLE_PREFIX."users` ADD `rprelationspm` int(11) NOT NULL;");
  }

	// Variablen einfügen
  include MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("member_profile", "#".preg_quote('{$awaybit}')."#i", '{$awaybit} {$add_relation}{$show_relation}');
	find_replace_templatesets("usercp_options", "#".preg_quote('{$board_style}')."#i", '{$rprelationspm}{$board_style}');
}

function rprelations_deactivate()
{
	global $db, $mybb;

  // Variablen entfernen
  include MYBB_ROOT."/inc/adminfunctions_templates.php";
  find_replace_templatesets("member_profile", "#".preg_quote('{$add_relation}{$show_relation}')."#i", '', 0);
  find_replace_templatesets("usercp_options", "#".preg_quote('{$rprelationspm}')."#i", '', 0);
}

function rprelations_member_profile_end()
{
	global $db, $mybb, $templates, $add_relation, $memprofile, $show_relation;
	$uid = $mybb->user['uid'];

	// Man kann sich nicht selbst hinzufügen! - Und Gäste haben ebenfalls keine Berechtigung.
	if($uid != $memprofile['uid'] && !empty($uid)) {
		$relvalue = $db->fetch_field($db->query("SELECT relation FROM ".TABLE_PREFIX."rprelations WHERE ruid = '$memprofile[uid]' AND suid = '$uid'"), "relation");
		eval("\$add_relation = \"".$templates->get("member_profile_rprelations_add")."\";");
	}

	// Relation in die Datenbank eintragen
	$addrel = $mybb->input['addrel'];
	if($addrel && $mybb->request_method == "post") {
		$relation = $mybb->get_input('relation');
		$new_record = array(
			"suid" => $uid,
			"ruid" => $addrel,
			"type" => $db->escape_string($mybb->get_input('type')),
			"relation" => $db->escape_string($mybb->get_input('relation'))
		);

		$check = $db->fetch_field($db->query("SELECT relation FROM ".TABLE_PREFIX."rprelations WHERE ruid = '$addrel' AND suid = '$uid'"), "relation");

		if($check) {
			$db->update_query("rprelations", $new_record, "ruid = '$addrel' AND suid = '$uid'");
			$subject = "Ich habe meine Beziehung zu dir verändert!";
			$message = "Hi! Ich habe soeben die Beziehung zu deinem Charakter in <i>{$relation}</i> verändert. Du kannst diese Angabe jederzeit in meinem Profil löschen, sollte sie dir nicht (mehr) gefallen. Bitte denk daran, auch die Ansicht deines Charakters über meinen zu überarbeiten, sollte sich etwas daran verändert haben.";
		}
		else {
			$db->insert_query("rprelations", $new_record);
			$subject = "Ich habe dich meiner Beziehungskiste hinzugefügt!";
			$message = "Hi! Ich habe deinen Charakter gerade als <i>{$relation}</i> zu meiner Beziehungskiste hinzugefügt. Du kannst diese Angabe jederzeit in meinem Profil löschen, sollte sie dir nicht (mehr) gefallen. Bitte denk daran, meinen Charakter ebenfalls zu deinem Profil hinzuzufügen! :)";
		}

	$relationspmcheck = $db->fetch_field($db->query("SELECT rprelationspm FROM mybb_users where uid = '$addrel'"), "rprelationspm");

		if($relationspmcheck == "1") {

		// PN bei neuer/veränderter Relation
		require_once MYBB_ROOT . "inc/datahandlers/pm.php";
		$pmhandler = new PMDataHandler();

		$pm = array(
				"subject" => $subject,
				"message" => $message,
				"fromid" => $uid,
				"toid" => $addrel
		);

		$pmhandler->set_data($pm);

		// PM versenden
		if (!$pmhandler->validate_pm()) {
				$pm_errors = $pmhandler->get_friendly_errors();
		}
		else {
				$pminfo = $pmhandler->insert_pm();
		}

	}

		redirect("member.php?action=profile&uid={$uid}");
	}

	// Relations im Profil ausgeben
	$types = array("positiv", "neutral", "negativ");
	$uid = $mybb->user['uid'];
	foreach($types as $type) {
		$relation_bit = "";
		eval("\$relation_none = \"".$templates->get("member_profile_rprelations_none")."\";");
		$query = $db->query("SELECT * FROM ".TABLE_PREFIX."rprelations LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.uid = ".TABLE_PREFIX."rprelations.ruid WHERE suid = '$memprofile[uid]' AND type = '$type' AND ruid IN(SELECT uid FROM ".TABLE_PREFIX."users) ORDER by relation DESC");
		while($relation = $db->fetch_array($query)) {
				$delete_rel = "";
				$relation_none = "";
				$useravatar = "<img src=\"{$relation[avatar]}\" style=\"width: 30px;\" / >";
				$relation['profilelink'] = build_profile_link($relation['username'], $relation['ruid']);
				if($uid == $relation['suid'] OR $uid == $relation['ruid']) {
					$delete_rel = "<a href=\"member.php?action=profile&delrel={$relation[rid]}\">[Löschen]</a>";
				}
				eval("\$relation_bit .= \"".$templates->get("member_profile_rprelations_bit")."\";");
		}
		eval("\$relation_type_bit .= \"".$templates->get("member_profile_rprelations_types")."\";");
	}
	eval("\$show_relation = \"".$templates->get("member_profile_rprelations")."\";");

	// Relations löschen
	$delete = $mybb->input['delrel'];
	if($delete) {
		$db->delete_query("rprelations", "rid = '$delete'");
		redirect("member.php?action=profile&id={$uid}");
	}

}

function rprelations_usercp() {

	global $mybb, $user, $templates, $rprelationspmcheck, $rprelationspm;

	if(isset($mybb->user['rprelationspm']) && $mybb->user['rprelationspm'] == 1)
	{
		$rprelationspmcheck = "checked=\"checked\"";
	}
	else
	{
		$rprelationspmcheck = "";
	}

	eval("\$rprelationspm = \"".$templates->get("usercp_options_rprelationspm")."\";");

}

function rprelations_usercp_options()
{
	global $mybb, $db;

	$uid = $mybb->user['uid'];

	$new_record = array(
		"rprelationspm" => $mybb->get_input('rprelationspm', MyBB::INPUT_INT)
	);
		$db->update_query("users", $new_record, "uid = '$uid'");

}

?>
