<?PHP

###############################################################
#                                                             #
# Fix Common Problems copyright 2015-2016, Andrew Zawadzki    #
#                                                             #
###############################################################

$communityPaths['autoUpdateSettings'] = "/boot/config/plugins/community.applications/AutoUpdate.json";
$fixPaths['tempFiles'] = "/tmp/fix.common.problems";
$fixPaths['errors'] = $fixPaths['tempFiles']."/errors.json";

require_once("/usr/local/emhttp/plugins/fix.common.problems/include/helpers.php");

function addError($description,$action) {
  global $errors;
  $errors .= "<tr><td><font color='red'>$description</font></td><td>$action</td></tr>";
}

function addLinkButton($buttonName,$link) {
  $link = str_replacE("'","&quot;",$link);
  return "<input type='button' value='$buttonName' onclick='window.location.href=&quot;$link&quot;'>";
}
function addButton($buttonName,$action) {
  $action = str_replace("'","&quot;",$action);
  return "<input type='button' value='$buttonName' onclick='$action'>";
}

$communityApplicationsInstalled = is_file("/var/log/plugins/community.applications.plg");

switch ($_POST['action']) {
  case 'scan':
    exec("/usr/local/emhttp/plugins/fix.common.problems/scripts/scan.php");
    $errors = readJsonFile($fixPaths['errors']);
    
    if ( ! $errors ) {
      echo "No problems found";
    } else {
      echo "<table class='tablesorter'>";
      echo "<thead><th>Error Found</th><th>Suggested Fix</th></thead>";
      foreach ($errors as $error) {
        echo "<tr><td width='40%'>".$error['error']."</td><td>".$error['suggestion']."</td></tr>";
      }
      echo "</table>";
    }
    break;
}
?>