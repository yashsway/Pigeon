//Database to hold report objects
var reports = new Array();
var ID, name, phone, email, department, request, custom_request, summary, details, priority, date, time, duration, admin_priority;
var newReport_valid = false;
var editReport_valid = false;
var newReport_complete = false;
//TEST: all of the following arrays are test report data
var test_names = ['Peter Gregory','Rade Kuruc','Jaimie Dickson','Marta Prancho','Mia Cai','Goranka Gaechesca'];
var test_phones = ['905-525-9140','905-525-9140','905-525-9140','905-525-9140','905-525-9140','905-525-9140'];
var test_emails = ['gregp@mcmaster.ca','kurucr@mcmaster.ca','jdickso@mcmaster.ca','mprancho@mcmaster.ca','caimi@mcmaster.ca','gacesag@mcmaster.ca'];
var test_departments = ['Residence Admissions','Residence Admissions','Residence Admissions','Residence Admissions','Residence Admissions','Residence Admissions'];
var test_requests = ['Other','Other','Other','Other','Other','Other'];
var test_customs= ['test','test','test','test','test','test'];
var test_summaries = ['Sample Report','Sample Report','Sample Report','Sample Report','Sample Report','Sample Report'];
var test_details = ['test','test','test','test','test','test'];
var test_priorities = ["Low","Medium","Medium","Low","High","High"];
var test_dates = ['04/01/2015','04/01/2015','04/01/2015','04/01/2015','04/01/2015','04/01/2015'];
var test_times = ['8:30 AM','8:30 AM','8:30 AM','8:30 AM','8:30 AM','8:30 AM'];

//-----------Backbone-----------
//Report ADT
function report(id,na,ph,em,dep,req,cus,summ,det,pri,dat,tim,dur,adm,nte,del){
    this.ID = id;
    this.name = na;
    this.phone = ph;
    this.email = em;
    this.department = dep;
    this.request = req;
    this.custom_request = cus;
    this.summary = summ;
    this.details = det;
    this.priority = pri;
    this.date = dat;
    this.time = tim;
    this.duration = 2; //Default value of 2 days
    this.admin_priority = "";
    this.admin_notes = "";
    this.markedForDeletion = false;
}
//-------------Database------------
//Pushes report objects into database (Local arrays currently, switch to SQL later!)
function databaseBuilder_initial(){
    for(var i=0;i<6;i++){
        reports.push(new report(hash(i),test_names[i],test_phones[i],test_emails[i],test_departments[i],test_requests[i],test_customs[i],test_summaries[i],test_details[i],test_priorities[i],test_dates[i],test_times[i]));
    }
    //TEST: Console msg
    console.log("Database Build Complete!"); //TEST
}
//Pushes new report object into database
function databaseEntry(rep){
    reports.push(rep);
    return rep.ID;
}
//Updates fields of a certain report in the database
/*function databaseUpdate(id,field,value){
    reports[id].field = value;
}*/
//-------------Database Utilities-------------
//Deletes specified report from the database
function database_dataDeleter(id){
   reports.splice(database_indexReturn(id),1);
   //TEST: console msg
   console.log("Report #" + id + " deleted.");
}
//Returns the index of the specified report
function database_indexReturn(id){
    for(var i=0;i<reports.length;i++){
        if(id==reports[i].ID){
            return i;
        }
    }
}
//ID Hashing Function
function hash(n){
    return n + "" + Math.floor((Math.random()*1000)+1);
}
//-----------All Validation-----------------
//Validation colors
function validationColors(val,regEx,obj,mode,locale){
    if(mode==0){
        if(regEx==false){
            if(locale==1){
                $(obj).parent().css('background-color','pink');
            }else{
                $(obj).css('background-color','pink');
            }
        }else if(regEx==true){
            if(locale==1){
                $(obj).parent().css('background-color','#CBE896');
            }else{
                $(obj).css('background-color','#CBE896');
            }
        }
    }
    else if(regEx.test(val)){
        if(locale==1){
            $(obj).parent().css('background-color','#CBE896');
        }else{
            $(obj).css('background-color','#CBE896');
        }
    }else{
        if(locale==1){
            $(obj).parent().css('background-color','pink');
        }else{
            $(obj).css('background-color','pink');
        }
    }
}
//New Report Validation
function newReport_validation(){
    $("#newReport_submit").mouseover(function () {
        //Name Validation
        var regX = /^[a-z|A-Z|\s*]+$/i; //First name and/or last name (with a space inbetween) No numbers or symbols allowed
        validationColors($("#newReport_name").val(),regX,"#newReport_name",1,1);
        //Phone Validation
        regX = /((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4})(\s?(x\d{5})))|((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4}))/g; //Standard US/Canadian Phone # along with an optional 5 digit extension beginning with an 'x' appended to the end /w or /wo a space
        validationColors($("#newReport_phone").val(),regX,"#newReport_phone",1,1);
        //Email Validation
        regX = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/g; //Standard email.
        validationColors($("#newReport_email").val(),regX,"#newReport_email",1,1);
        //Department Validation
        if($("#newReport_department").val()=="blank" || $("#newReport_department").text()==""){
            validationColors($("#newReport_department").val(),false,"#newReport_department",0,1);
        }else{
            validationColors($("#newReport_department").val(),true,"#newReport_department",0,1);
        }
        //Request Category Validation
        var v = $("#newReport_requestCategory").val();
        if(v=="blank"){
            validationColors($("#newReport_requestCategory").val(),false,"#newReport_requestCategory",0,1);
        }else{
            validationColors($("#newReport_requestCategory").val(),true,"#newReport_requestCategory",0,1);
            //Remove color coding in this section when the category is changed back to something different from 'Other'
            if(v!="Other"){
                //Clear background coloring
                $("#newReport_otherRequest").parent().css('background-color','');
                //And residual text
                $("#newReport_otherRequest").val('');
            }
        }
        //Other Request Validation
        regX = /.{5,}/g;
        if($("#newReport_requestCategory").val()=="Other"){
            if($("#newReport_otherRequest").text()==""){
                validationColors($("#newReport_otherRequest").val(),regX,"#newReport_otherRequest",1,1);
            }else{
                validationColors($("#newReport_otherRequest").val(),regX,"#newReport_otherRequest",1,1);
            }
        }
        //Summary Validation
        regX = /.{5,}/g; //Standard email.
        validationColors($("#newReport_summary").val(),regX,"#newReport_summary",1,1);
        //Date & Time Validation
        regX = /.{10,}/g;
        var regX2 = /(\d{1,2}:?\s?\d{2,2}\s?(AM|am|PM|pm))|(anytime|Anytime)/g;
        //Color coding for this section is separate for both fields. Locale argument is therefore 0, meaning 'local' instead of 1, which means 'parent'
        validationColors($("#newReport_date").val(),regX,"#newReport_date",1,0);
        validationColors($("#newReport_time").val(),regX2,"#newReport_time",1,0);
        //Priority Validation
        if ($("input[name='priority']:checked").val()) {
            validationColors($("#newReport_priority").val(),true,"#newReport_priority",0);
        }else if(!$("input[name='priority']:checked").val()){
            validationColors($("#newReport_priority").val(),false,"#newReport_priority",0);
        }
    });
}
//Pre Submission Validation Check for New Reports
function finalValidationCheck(formType){
    //rgb(255, 192, 203) = pink
    //rgb(203, 232, 150) = green
    if(formType=="newReport"){
        var fields = ["#newReport_name","#newReport_phone","#newReport_email","#newReport_department","#newReport_requestCategory","#newReport_otherRequest","#newReport_summary","#newReport_priority","#newReport_date","#newReport_time"];
        for(var i=0;i<fields.length;i++){
            //TEST: Progress bar
            progressBar_modify("#newReport_progress",5);
            //TEST: console msg
            //console.log(fields[i]+ ": self->" + $(fields[i]).css('background-color') + " parent->" + $(fields[i]).parent().css('background-color'));
            if(i==5){
                if(($(fields[i]).parent().css('background-color')=='rgba(0, 0, 0, 0)') | ($(fields[i]).parent().css('background-color')=='rgb(203, 232, 150)')){
                    newReport_valid == true;
                }else{
                    newReport_valid = false;
                    break;
                }
            }
            else if(i>=7){
                if(($(fields[i]).css('background-color')=='rgb(203, 232, 150)')){
                    newReport_valid = true;
                }else{
                    newReport_valid = false;
                    break;
                }
            }
            else if(($(fields[i]).parent().css('background-color')=='rgb(203, 232, 150)')){
                newReport_valid = true;
            }else{
                newReport_valid = false;
                break;
            }
        }
    }else if(formType=="editReport"){
        var fields = ["#editReport_name","#editReport_phone","#editReport_email","#editReport_date","#editReport_time"];
        for(var i=0;i<fields.length;i++){
            if(($(fields[i]).parent().css('background-color')=='rgb(203, 232, 150)')){
                editReport_valid = true;
            }else{
                editReport_valid = false;
                break;
            }
        }
    }
}
//Edit Report validation
function editReport_validation(){
    $("#editReport_save").mouseover(function(){
        //Name validation
        var regX = /^[a-z|A-Z|\s*]+$/i; //First name and/or last name (with a space inbetween) No numbers or symbols allowed
        validationColors($("#editReport_name").val(),regX,"#editReport_name",1,1);
        //Phone Validation
        regX = /((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4})(\s?(x\d{5})))|((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4}))/g; //Standard US/Canadian Phone # along with an optional 5 digit extension beginning with an 'x' appended to the end /w or /wo a space
        validationColors($("#editReport_phone").val(),regX,"#editReport_phone",1,1);
        //Email Validation
        regX = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/g; //Standard email.
        validationColors($("#editReport_email").val(),regX,"#editReport_email",1,1);
        //Date & Time Validation
        regX = /.{10,}/g;
        validationColors($("#editReport_date").val(),regX,"#editReport_date",1,1);
        regX= /(\d{1,2}:?\s?\d{2,2}\s?(AM|am|PM|pm))|(anytime|Anytime)/g;
        validationColors($("#editReport_time").val(),regX,"#editReport_time",1,1);
    });
}
//------------New Report UI/Utilities/Tools---------------
function newReport_message(msg){
    //Display the message received
    $("#newReport_infoMsg").html(msg+"&nbsp&nbsp");
    //Set the default message back on after 5s
    setTimeout(function(){
        $("#newReport_infoMsg").html("All fields are required, except details.&nbsp&nbsp")
    },5000);
}
//Other category field is only enabled when request category dropdown is selected as 'other'
$("#newReport_requestCategory").on("click",function(){
    if($(this).val()=="Other"){
        $("#newReport_otherRequest").prop('disabled',false);
    }else{
        $("#newReport_otherRequest").prop('disabled',true);
    }
});
//Clear button of the new Report formn
$("#newReport_clear").click(function(){
    $("#newReport_name").val('');$("#newReport_name").parent().css('background-color','');
    $("#newReport_phone").val('');$("#newReport_phone").parent().css('background-color','');
    $("#newReport_email").val('');$("#newReport_email").parent().css('background-color','');
    $("#newReport_department").val('blank');$("#newReport_department").parent().css('background-color','');
    $("#newReport_requestCategory").val('blank');$("#newReport_requestCategory").parent().css('background-color','');
    $("#newReport_otherRequest").val('');$("#newReport_otherRequest").parent().css('background-color','');
    $("#newReport_summary").val('');$("#newReport_summary").parent().css('background-color','');
    $("#newReport_details").val('');
    $('input[name="priority"]').prop('checked', false);$("#newReport_priority").css('background-color','');
    $('#newReport_date').val('');$('#newReport_date').css('background-color','');
    $('#newReport_time').val('');$('#newReport_time').css('background-color','');
    $("#newReport_otherRequest").prop('disabled',true);
    //Renable the Submit button
    $("#newReport_submit").attr('disabled',false);
    //Inform the user
    newReport_message("Form cleared! You may start over.");
});
//-----------New Report Submission--------------
function newReport_engine(){
    //New Report Validation
    newReport_validation();
    //Enable new report submission
    newReport_formSubmission();
}
//New Report Compilation
function newReport_compilation(){
    var temp = new report(hash(reports.length),$("#newReport_name").val(),$("#newReport_phone").val(),$("#newReport_email").val(),$("#newReport_department").val(),$("#newReport_requestCategory").val(),$("#newReport_otherRequest").val(),$("#newReport_summary").val(),$("#newReport_details").val(),$("input[type='radio'][name='priority']:checked").val(),$("#newReport_date").val(),$("#newReport_time").val());
    //TEST: progress bar
    progressBar_modify("#newReport_progress",15);
    return temp;
}
//New Report submit
function newReport_formSubmission(){
    $("#newReport_submit").on('click',function(){
        //Check the form if everything is valid (progress bar is the 3rd & final validation step)
        finalValidationCheck("newReport");
        if(newReport_valid==true){
            //Inform the user that the form is valid
            newReport_message("Looks great! Thanks!");
            //Compile the form, add to database, update progress bar and make an entry in the table
            var newEntry_id = databaseEntry(newReport_compilation());
            //Test: progress bar
            progressBar_modify("#newReport_progress",20);
            rowBuilder(newEntry_id);
            //Bind view & delete buttons //TODO: Automatically BIND view and delete on creation of new row
            detailedReportBuilder();
            reportDeletion();
            //Test: progress bar
            progressBar_modify("#newReport_progress",5);
            //Disable the submit button to prevent multiple submissions
            $("#newReport_submit").attr('disabled',true);
            //Clear form and close AFTER 3.5s IF progress bar is FULL
            if($("#newReport_progress").attr('aria-valuenow')==100){
                setTimeout(function(){
                    $("#newReport_clear").trigger("click");
                    $("#newReport_close").trigger("click");
                },3500);
            }else{
                //Inform the user that something went wrong
                newReport_message("Submission failed! :( Something went wrong, try again later.");
                //TEST: console msg
                console.log("Failed to record new report. :( " + $("#newReport_progress").attr('aria-valuenow'));
            }
            //The valid boolean will be set to false after the report goes into the database
            newReport_valid = false;
        }else{
            //Inform the user that the form has some invalid fields
            newReport_message("Correct the fields in <b>red</b> first!");
        }
    });
}
//--------------Editing Reports--------------
function editReport_engine(){
    //Enable viewing of forms
    viewEditForm();
    //Enable validation
    editReport_validation();
}
function editReport_message(msg,time){
    //Display the message received
    $("#editReport_infoMsg").html(msg+"&nbsp&nbsp");
    //Set the default message back on after 5s
    setTimeout(function(){
        $("#editReport_infoMsg").html("Hover over information for additional help text.");
    },time);
}
function editReport_updateChanges(id){
    //DATABASE UPDATE (should me moved up to database section after backend is complete)
    reports[id].name = $("#editReport_name").val();
    reports[id].phone = $("#editReport_phone").val();
    reports[id].email = $("#editReport_email").val();
    reports[id].date = $("#editReport_date").val();
    reports[id].time = $("#editReport_time").val();
    reports[id].admin_priority = $("input[type='radio'][name='adminPriority']:checked").val();
    reports[id].duration = $("#editReport_durationSlider").val();
    reports[id].admin_notes = $("#editReport_notes").val();
}
function viewEditForm(){
    $("#edit_issue").on("click",function(){
        //TEST: Get the ID of the report to be edited
        var temp_index = database_indexReturn($(this).parent().parent().attr("id"));
        //TEST: Fill the client part of the edit form with available info
        $("#editReport_name").val(reports[temp_index].name);
        $("#editReport_phone").val(reports[temp_index].phone);
        $("#editReport_email").val(reports[temp_index].email);
        $("#editReport_date").val(reports[temp_index].date);
        $("#editReport_time").val(reports[temp_index].time);
        //Setup the duration slider
        $("#editReport_durationSlider").noUiSlider({
            start: 3,
            step: 1,
            connect: "lower",
            orientation: "horizontal",
            range: {
                'min': 1,
                'max': 30
            },
            format: wNumb({
                decimals: 0
            })
        });
        //Show the initial value
        $("#duration_tooltip").text(reports[temp_index].duration + " day(s)");
        //Update the value in the label so the user knows what value the slider is at
        $("#editReport_durationSlider").on('slide',function(){
            $("#duration_tooltip").text($(this).val()+" day(s)");
        });
        //Disable itself
        $(this).prop('disabled',true);
        //Disable the resolution tools
        $(".resolutionTools").prop('disabled',true);
        //Show the edit form
        $("#editReport").show();
        //Show the save & discard buttons
        $(".saveTools").show();
        //Inform the user
        editReport_message("Editing report...",30000);
    });
    function closeEditForm(){
        //Destroy the slider object
        $("#editReport_durationSlider")[0].destroy();
        //Hide the edit report form
        $("#editReport").hide();
        //Hide the save tools
        $(".saveTools").hide();
        //Enable the edit button
        $("#edit_issue").prop('disabled',false);
        //Enable the resolution tools
        $(".resolutionTools").prop('disabled',false);
    }
    $("#editReport_save").on("click",function(){
        //Validate client section
        finalValidationCheck("editReport");
        if(editReport_valid==true){
            //Update database entry
            editReport_updateChanges(database_indexReturn($(this).parent().parent().attr("id")));
            //Close edit form & related
            closeEditForm();
            //Inform the user
            editReport_message("Changes saved! Reopen this window to see changes.",5000);
            //Refresh the report listing (WILL NEED TO BE REMOVED WHEN DATABASE IS INTEGRATED)
            rowBuilder_refresh();
            //Reset the boolean
            editReport_valid = false;
        }else{
            //Inform the user that the form has some invalid fields
            editReport_message("Correct the fields in <b>red</b> first!",3500);
        }
    });
    $("#editReport_discard").on("click",function(){
        //Close edit form & related
        closeEditForm();
        //Clear the edit form //TODO: probably unnecessary since hitting edit loads info while replacing existing content

        //Inform the user
        editReport_message("Changes discarded!",5000);
    });
}
//--------------Reports Table Display------------
//Table row builder
/*function rowBuilder_initial(){
    //var temp_priority;
    //Add an entry in the panel for each report
    for(var i=0;i<reports.length;i++){
        //Determine priority in a client-understandable form
        //temp_priority = priorityString(reports[i].priority);
        //Report ID is also the ID of the table row.
        //Replace priority column with admin priority setting if admin priority field is NOT empty
        if(reports[i].admin_priority!=""){
            $(".main-panel > tbody").append('<tr id="' + reports[i].ID + '"><td class="report-elements report-id">' + reports[i].ID + '</td><td class="report-elements report-title">' + reports[i].summary + '</td><td class="report-elements report-date">' + reports[i].date + '</td><td class="report-elements report-duration">' + reports[i].duration + ' day(s)</td><td class="report-elements report-urgency">' + priorityFlagCodeGenerator(reports[i].admin_priority) + '</td><td class="report-elements report-tools"><button class="btn btn-default view">View</button><button class="btn btn-success restore" id="restore'+ reports[i].ID + '"><span class="fa fa-undo"></span></button><button class="btn btn-danger delete">Delete</button></td></tr>');
        }else{
            $(".main-panel > tbody").append('<tr id="' + reports[i].ID + '"><td class="report-elements report-id">' + reports[i].ID + '</td><td class="report-elements report-title">' + reports[i].summary + '</td><td class="report-elements report-date">' + reports[i].date + '</td><td class="report-elements report-duration">' + reports[i].duration + ' day(s)</td><td class="report-elements report-urgency">' + priorityFlagCodeGenerator(reports[i].priority) + '</td><td class="report-elements report-tools"><button class="btn btn-default view">View</button><button class="btn btn-success restore" id="restore'+ reports[i].ID + '"><span class="fa fa-undo"></span></button><button class="btn btn-danger delete">Delete</button></td></tr>');
        }
    }
    //Build the detailed view
    detailedReportBuilder();
    reportDeletion();
}*/
//Table Row Adder
function rowBuilder(id){
    var index = database_indexReturn(id);
    $(".main-panel > tbody").append('<tr id="' + reports[index].ID + '"><td class="report-elements report-id">' + reports[index].ID + '</td><td class="report-elements report-title">' + reports[index].summary + '</td><td class="report-elements report-date">' + reports[index].date + '</td><td class="report-elements report-duration">' + reports[index].duration + ' day(s)</td><td class="report-elements report-urgency">' + priorityFlagCodeGenerator(reports[index].priority) + '</td><td class="report-elements report-tools"><button class="btn btn-default view">View</button><button class="btn btn-success restore" id="restore'+ reports[index].ID + '"><span class="fa fa-undo"></span></button><button class="btn btn-danger delete">Delete</button></td></tr>');
    //Test: progress bar
    progressBar_modify("#newReport_progress",10);
}
function rowBuilder_refresh(){
    $(".main-panel > tbody").empty();
    rowBuilder_initial();
}
//-----------------Reports Table Entries Tools/Utilities--------------
//Priority Flag HTML generator
function priorityFlagCodeGenerator(value){
    switch(value){
        case "Inactive":
            return '<img src="assets/icons/grey-flag.png"/>';
        case "Low":
            return '<img src="assets/icons/green-flag.png"/>';
        case "Medium":
            return '<img src="assets/icons/orange-flag.png"/>';
        case "High":
            return '<img src="assets/icons/red-flag.png"/>';
        default:
            return '<img src="assets/icons/risk.png"/>';
    }
}
function reportRestoration(id,deleteButton,delTimeout){
    //Report restoration //TODO: (FIX ID ACQUISITION/DELETE BUTTON/TIMER STOPPING - WHEN MULTIPLE REPORTS ARE BEING DELETED, IT WILL BE BUGGY)
    $("#restore"+id).on('click',function(){
        //Stop the timer set by the setTimeout function in the "reportDeletion()"
        clearTimeout(delTimeout);
        //Delete the pietimer
        $("#timer"+id).empty();
        //Hide itself
        $(this).hide();
        //Remove the deletion tag/marking on the report
        reports[database_indexReturn(id)].markedForDeletion = false;
        //Remove the greyOut
        $("#"+id).removeClass("greyOut");
        //Show the delete button again (after a delay)
        setTimeout(function() {
            $(deleteButton).show();
        }, 3000);
    });
}
function reportDeletion(){
    //Allow report deletion
    $(".delete").on("click",function(){
        var obj = $(this);
        var reportID = $(this).parent().parent().attr('id');
        //var toggle = 0;
        //Mark report for deletion
        reports[database_indexReturn(reportID)].markedForDeletion = true;
        //TEST: Console msg
        console.log("Report #" + reportID + " marked for deletion.");
        //Remove report listing (after a delay)
        $("#"+reportID).addClass("greyOut");
        $(obj).css('display','none');
        //Add a visual timer
        $(obj).parent().append('<div class="timer" id="timer'+ reportID + '"></div>');
        //Place the timer in the element inserted above
        newTimer("#timer"+reportID,20);
        //Delete the report from the database and the display after the time
        var deleteTimeout = setTimeout(function(){
            //Remove the table entry
            obj.parent().parent().remove();
            //Delete the report data from the database
            database_dataDeleter(reportID);
        },20000);
        //Show the restoration button
        $("#restore"+reportID).show();
        //Allow restoration
        reportRestoration(reportID,obj,deleteTimeout);
        //The timer can be toggled between pause/resume when clicked (FEATURE REMOVED)
        /*$("#timer"+reportID).on('click',function(){
            if(toggle==0){
                //Pause the timer
                $("#timer"+reportID).pietimer('pause');
                toggle=1;
            }else if(toggle==1){
                //Resume the timer
                $("#timer"+reportID).pietimer('start');
                toggle=0;
            }
        });*/
        //**Check detaileReportBuilder below for additional deletion utility**
    });
}
//Detailed Report View Generator (Asynchronous elements inside)
function detailedReportBuilder(){
    //Detailed Report Modal View for View Button
    $(".report-tools .view").attr("data-toggle","modal");
    $(".report-tools .view").attr("data-target","#full-info");
    //Customize all fields for the exact report that was clicked
    $(".view").on("click", function (){
        //Acquire the index of the report entry
        var query_ID = $(this).parent().parent().attr('id');
        var temp_index = database_indexReturn(query_ID);
        //Set the ID of the modal container to the report ID (USED BY THE EDITING REPORT ENGINE)
        $(".modal-content").attr("id",query_ID);
        //Change all the info fields to the data of the corresponding report
        $("#full-info-title").text("#" + reports[temp_index].ID + " " + reports[temp_index].summary);
        $(".full-info-text.name").text(reports[temp_index].name);
        $(".full-info-text.phone").text(reports[temp_index].phone);
        $(".full-info-text.email").text(reports[temp_index].email);
        $(".full-info-text.department").text(reports[temp_index].department);
        if(reports[temp_index].request=="Other"){
            $(".full-info-text.request").text(reports[temp_index].custom_request);
        }else{
            $(".full-info-text.request").text(reports[temp_index].request);
        }
        $(".full-info-text.date").text(reports[temp_index].date);
        $(".full-info-text.details").text(reports[temp_index].details);
        $(".full-info-text.priority").text(reports[temp_index].priority); //priorityString() call REMOVED
        $(".full-info-text.time").text(reports[temp_index].time);
        //Admin-Set information changed below
        $(".full-info-text.adminPriority").text(reports[temp_index].admin_priority);
        $(".full-info-text.duration").text("Will take approximately " + reports[temp_index].duration+" day(s) to complete");
        $(".full-info-text.notes").text(reports[temp_index].admin_notes);
        //Final Color-coding
        detailedReport_ColorCoding(reports[temp_index].priority,"priority");
        detailedReport_ColorCoding(reports[temp_index].admin_priority,"adminPriority");
        //Disable Resolution/Editing when the report has been marked for deletion
        if(reports[database_indexReturn(query_ID)].markedForDeletion==true){
            $(".resolutionTools").hide();
            $("#edit_issue").hide();
        }else{
            $(".resolutionTools").show();
            $("#edit_issue").show();
        }
    });
}
//Detailed Report Color-coding
function detailedReport_ColorCoding(value,field){
    if(field=="priority"){
        switch(value){
            case "Low":
                $(".priority_container").css("background-color","#7AC74F");
                break;
            case "Medium":
                $(".priority_container").css("background-color","#E8C571");
                break;
            case "High":
                $(".priority_container").css("background-color","salmon");
                break;
            default:
                $(".priority_container").css("background-color","black");
                break;
        }
    }else if(field=="adminPriority"){
        switch(value){
            case "Inactive":
                $(".adminPriority_container").css("background-color","rgba(205, 205, 205, 0.5)");
                break;
            case "Low":
                $(".adminPriority_container").css("background-color","rgba(122, 199, 79, 0.5)");
                break;
            case "Medium":
                $(".adminPriority_container").css("background-color","rgba(232, 197, 113, 0.5)");
                break;
            case "High":
                $(".adminPriority_container").css("background-color","rgba(250, 128, 114, 0.5)");
                break;
            default:
                $(".adminPriority_container").css("background-color","");
                break;
        }
    }
}
//--------------------UTILITIES/UI--------------------
//Progress Bar Controller
function progressBar_modify(elem,quant){
    //If quant = 0, reset the progress bar
    if(quant==0){
        $(elem).css('width',0+'%');
        $(elem).attr('aria-valuenow',0);
    }else{
        var temp = (parseInt($(elem).attr('aria-valuenow'))+quant);
        $(elem).attr('aria-valuenow',temp);
        $(elem).css('width',temp+"%");
        //Reset after 3s
        setTimeout(function(){
          $(elem).css('width',0+'%');
            $(elem).attr('aria-valuenow',0);
        },3000);
    }
}
//Report Deletion delay timer
function newTimer(elem,time,obj,id){
    $(elem).pietimer({
        seconds: time,
        color: 'rgba(0, 0, 0, 0.8)',
        height: 25,
        width: 25
    });
    $(elem).pietimer('start');
}
//Priority String Setter
/*function priorityString(value){
    var p;
    switch(value){
        case 0:
            p = "Low";
            break;
        case 1:
            p = "Medium";
            break;
        case 2:
            p = "High";
            break;
        default:
            p = "Error!";
            break;
    }
    return p;
}*/
//---------------Page Load---------------
// Functions to execute upon page load
$(document).ready(function(){
    //New Report Modal View for Report button
    $("#report").attr("data-toggle","modal");
    $("#report").attr("data-target","#file-new-report");
    //Enable report editing
    editReport_engine();
    //Enable new reports
    newReport_engine();
    //Datedroppers
    $("#newReport_date").dateDropper();
    $("#editReport_date").dateDropper();
    //Enable tooltips
    $('[data-toggle="tooltip"]').tooltip();
    //Build database
    //databaseBuilder_initial();
    //Build table row & report listing
    //rowBuilder_initial();
    //MOVED: Call to enable detailed report building
    detailedReportBuilder();
});
