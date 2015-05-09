//Report Data Variables

// Functions to execute upon page load
$(document).ready(function (){
    //Detailed Report Modal View for View Button
    $(".report-tools .view").attr("data-toggle","modal");
    $(".report-tools .view").attr("data-target","#full-info");
    //New Report Modal View for Report button
    $("#report").attr("data-toggle","modal");
    $("#report").attr("data-target","#file-new-report");
    //Datedropper
    $("#newReport_date").dateDropper();
});