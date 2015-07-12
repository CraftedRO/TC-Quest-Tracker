<?php

require_once("config.php");

function printTableBody($limit)
{
  global $db, $characters_db, $world_db, $url;

  if (!is_int($limit))
    return;

  $query = sprintf("SELECT t1.id, t2.LogTitle, COUNT(t1.quest_abandon_time) AS abandoned_times, COUNT(t1.quest_complete_time) AS completed_times, MAX(t1.quest_abandon_time) AS last_abandoned, MAX(t1.quest_complete_time) AS last_completed " .
                   "FROM (SELECT id, quest_abandon_time, quest_complete_time, core_hash, core_revision FROM %s.quest_tracker) AS t1 " .
                   "JOIN (SELECT ID, LogTitle FROM %s.quest_template) AS t2 " .
                   "ON t1.id = t2.ID " .
                   "GROUP BY t1.id " .
                   "HAVING abandoned_times > 0 " .
                   "ORDER BY abandoned_times DESC " .
                   "LIMIT 0, %d",
                   $characters_db,
                   $world_db,
                   $limit);

  $result = $db->query($query);

  if (!$result)
    die("Error querying: " . $query);

  while (($row = $result->fetch_array()) != null)
  {
    printf("<tr><td><strong>%s</strong></td><td><a href=\"%s%s\">%s</a></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>",
           $row['id'],
           $url,
           $row['id'],
           $row['LogTitle'],
           $row['abandoned_times'],
           $row['completed_times'],
           $row['last_abandoned'],
           $row['last_completed']);
  }
}

?>
