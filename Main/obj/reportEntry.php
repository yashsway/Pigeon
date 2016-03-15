<?php
function reportEntry(){
    global $reports;
    if($GLOBALS['displayReports']==true){
        for($i=0;$i<count($reports);$i++){
            echo '<tr id="' . $reports[$i]->ID .'">';
            echo '<td class="report-elements report-id text ">' . htmlspecialchars($reports[$i]->ID,ENT_QUOTES,'UTF-8') . '</td>';
            echo '<td class="report-elements report-title text">' . htmlspecialchars(substr($reports[$i]->summary,0,60),ENT_QUOTES,'UTF-8') . '</td>';
            echo '<td class="report-elements report-icons report-resolution">' . resolutionFlagCodeGenerator($reports[$i]->resolved) . '</td>';
            echo '<td class="report-elements report-icons report-tag">' . tagCodeGenerator($reports[$i]->tag) . '</td>';
            echo '<td class="report-elements report-date text">' . htmlspecialchars($reports[$i]->_date,ENT_QUOTES,'UTF-8') . '</td>';
            echo '<td class="report-elements report-icons report-priority">' . priorityFlagCodeGenerator($reports[$i]->priority) . '</td>';
            echo '<td class="report-elements report-icons report-adminPriority">' . priorityFlagCodeGenerator($reports[$i]->admin_priority) . '</td>';
            echo '<td class="report-elements report-duration text">' . htmlspecialchars($reports[$i]->duration,ENT_QUOTES,'UTF-8') . '</td>';
            echo '<td class="report-elements report-tools"><button class="btn btn-default view org-repTools">View</button><img class="view alt-repTools" src="assets/icons/zoom.png"><button class="btn btn-success restore" id="restore' . $reports[$i]->ID . '"><span class="fa fa-undo"></span></button><button class="btn btn-danger delete org-repTools">Delete</button><img class="delete alt-repTools" src="assets/icons/delete.png"></td>';
            echo '</tr>';
        }
    }
}
?>
