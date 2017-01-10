<?PHP
/**
 *
 */
class SensorItemDbTable extends DbTable{
    function postDelete($id){
        $sqlRemoveTerms = "DELETE FROM homecontrol_term WHERE trigger_type=1 " 
                           ." AND trigger_id = ".$_SESSION['SelectedSensorToEdit']
                           ." AND trigger_subid = ".$id;
        $_SESSION['config']->DBCONNECT->executeQuery($sqlRemoveTerms);
    }
}

?>