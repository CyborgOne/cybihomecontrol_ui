<?PHP

class HomeControlCron {
    private $CRON_ROW;

    private $WEEKDAYS = array(0 => "sonntag", 1 => "montag", 2 => "dienstag", 3 =>
        "mittwoch", 4 => "donnerstag", 5 => "freitag", 6 => "samstag");

    private $SHORTWEEKDAYS = array(0 => "So.", 1 => "Mo.", 2 => "Di.", 3 => "Mi.", 4 =>
        "Do.", 5 => "Fr.", 6 => "Sa.");

    function HomeControlCron($homeControlCronDbRow) {
        $this->CRON_ROW = $homeControlCronDbRow;

    }


    function getId() {
        return $this->CRON_ROW->getNamedAttribute("id");
    }

    function getName() {
        return $this->CRON_ROW->getNamedAttribute("name");
    }

    function getBeschreibung() {
        return $this->CRON_ROW->getNamedAttribute("beschreibung");
    }

    function getStunde() {
        return $this->CRON_ROW->getNamedAttribute("stunde");
    }


    function getMinute() {
        return $this->CRON_ROW->getNamedAttribute("minute");
    }


    function getPauseLink() {
        $loginNeed = getPageConfigParam($_SESSION['config']->DBCONNECT,
            "loginForTimelinePauseNeed") == "J";
        $loggedIn = ($_SESSION['config']->CURRENTUSER->STATUS == "admin" || $_SESSION['config']->
            CURRENTUSER->STATUS == "user");

        if ($this->isNextExecutionCron() && (($loginNeed && $loggedIn) || !$loginNeed)) {
            if ($this->isCronPaused()) {
                return $this->getPauseDeactivationLink();
            } else {
                return $this->getPauseActivationLink();
            }
        } else {
            return new Spacer(0);
        }
    }


    private function getPauseActivationLink() {
        $lnk = new Link("?pauseCron=" . $this->getId(), new Text("Pause", 4));
        $lnk->setToolTip("Folgende Ausführung auslassen");

        return $lnk;
    }


    private function getPauseDeactivationLink() {
        $lnk = new Link("?unpauseCron=" . $this->getId(), new Text("Aktivieren", 4));
        $lnk->setToolTip("Pausierung aufheben");

        return $lnk;
    }

    function checkPauseLink() {
        if (isset($_REQUEST['pauseCron']) && $_REQUEST['pauseCron'] == $this->getId() && $this->isNextExecutionCron()) {
            $this->setPause(true);
        }
        if (isset($_REQUEST['unpauseCron']) && $_REQUEST['unpauseCron'] == $this->getId() && $this->isNextExecutionCron()) {
            $this->setPause(false);
        }
    }


    function getNextExecutionDayIndex() {
        $dayOfWeek = date("w", time());

        for ($iO = 0; $iO < 7; $iO++) {
            $weekDayIndex = ($dayOfWeek + $iO) <= 6 ? ($dayOfWeek + $iO) : ($dayOfWeek + $iO) -
                7;

            if ($this->CRON_ROW->getNamedAttribute($this->WEEKDAYS[$weekDayIndex]) == "J") {
                $std = $this->CRON_ROW->getNamedAttribute("stunde");
                $min = $this->CRON_ROW->getNamedAttribute("minute");

                if ((($std == date("H") && $min > date("i")) || ($std > date("H"))) || $iO > 0) {
                    return $weekDayIndex;
                }
            }
        }
    }


    function getNextExecutionTimeAsString() {
        $dayIndex = $this->getNextExecutionDayIndex();
        $ret = "";

        if (strlen($dayIndex) > 0 && $dayIndex >= 0 && $dayIndex <= 6) {
            $ret = $this->CRON_ROW->getNamedAttribute("wochentag");
            $ret .= " " . sprintf('%02d', $this->CRON_ROW->getNamedAttribute("stunde"));
            $ret .= ":" . sprintf('%02d', $this->CRON_ROW->getNamedAttribute("minute"));
        }
        return $ret;
    }

    function isNextExecutionCron() {
        $dayIndex = $this->CRON_ROW->getNamedAttribute("tagnr");
        if ($dayIndex >= 0 && $dayIndex <= 6) {
            $std = $this->CRON_ROW->getNamedAttribute("stunde");
            $min = $this->CRON_ROW->getNamedAttribute("minute");

            $rlNxtDay = date("w") < 6 ? date("w") + 1 : 0;

            $isCurrentDay = $dayIndex == date("w");
            $isNextDay = $dayIndex == $rlNxtDay;

            if (($isCurrentDay && ($std == date("H") && $min > date("i") || $std > date("H"))) ||
                ($isNextDay && ($std == date("H") && $min < date("i") || $std < date("H")))) {
                return true;
            }
        }
        return false;
    }

    function isCronPaused() {
        $sql = "SELECT 'X' FROM homecontrol_cron_pause WHERE cron_id = " . $this->getId();
        $result = $_SESSION['config']->DBCONNECT->executeQuery($sql);
        return (mysql_num_rows($result) > 0) && $this->isNextExecutionCron();
    }


    function setPause($b) {
        $sql = "";

        if ($b === true) {
            $sql = "INSERT INTO homecontrol_cron_pause (cron_id, pause_time) VALUES (" . $this->getId() . "," . time() . ")";
        } else {
            $sql = "DELETE FROM homecontrol_cron_pause WHERE cron_id=" . $this->getId();
        }

        $_SESSION['config']->DBCONNECT->executeQuery($sql);
    }
}

?>