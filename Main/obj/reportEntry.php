<?php
if($GLOBALS['displayReports']==true){
    for($i=0;$i<count($reports);$i++){
        echo '<tr id="' . $reports[$i]->ID .'">';
        echo '<td class="report-elements report-id">' . htmlspecialchars($reports[$i]->ID,ENT_QUOTES,'UTF-8') . '</td>';
        echo '<td class="report-elements report-title">' . htmlspecialchars($reports[$i]->summary,ENT_QUOTES,'UTF-8') . '</td>';
        echo '<td class="report-elements report-date">' . htmlspecialchars($reports[$i]->date,ENT_QUOTES,'UTF-8') . '</td>';
        echo '<td class="report-elements report-duration">' . htmlspecialchars($reports[$i]->duration . ' days',ENT_QUOTES,'UTF-8') . '</td>';
        echo '<td class="report-elements report-urgency">' . priorityFlagCodeGenerator($reports[$i]->priority) . '</td>';
        echo '<td class="report-elements report-tools"><button class="btn btn-default view">View</button><button class="btn btn-success restore" id="restore' . $reports[$i]->ID . '"><span class="fa fa-undo"></span></button><button class="btn btn-danger delete">Delete</button>';
        echo '</tr>';
    }
}
?>
