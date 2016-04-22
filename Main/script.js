//Database to hold report objects
var reports = new Array();
var ID, name, phone, email, department, request, custom_request, summary, details, priority, date, time, duration, admin_priority;
var newReport_valid = false;
var editReport_valid = false;
//--------------------AJAX Handler-------------------
var data = "";
function ajaxRequest(reqScript, returnDataType, reqData, callback){
    $.ajax({
        type: "POST",
        dataType: returnDataType,
        url: reqScript,
        data: reqData,
        success: function(data) {
            //console.log("AJAX request success.");
            //console.log(data);
            callback(data);
        },
        fail: function(){
            console.log("AJAX request failed.");
        },
        error: function(){
            console.log("Error on server-side!");
        }
    });
}
//AJAX background refreshes
function ajaxRefresh(mode,id){
    switch(mode){
            case 1:
                //NOTE: can change it to change the details from normal array
                ajaxRequest("databaseButler.php?reqType="+0+"&queryID="+id, "json", null, function(returnedData){
                    if(returnedData[0].error=="fail"){
                        console.log("Populating detailed view failed. Check Database Query.");
                    }else{
                        $("#full-info-title").text("#" + id + " " + returnedData[0].reportSummary.substr(0,60) + "...");
                        $(".full-info-text.name").text(returnedData[0].reportName);
                        $(".full-info-text.phone").text(returnedData[0].reportPhone);
                        $(".full-info-text.email").text(returnedData[0].reportEmail);
                        $(".full-info-text.department").text(returnedData[0].reportDepartment);
                        if(returnedData[0].reportRequest=="Other"){
                            $(".full-info-text.request").text(returnedData[0].reportCustomRequest);
                        }else{
                            $(".full-info-text.request").text(returnedData[0].reportRequest);
                        }
                        //CHANGES: removed reportDetails and populated field with summary instead
                        /*if(returnedData[0].reportDetails!=null){
                            $(".full-info-text.details").text(returnedData[0].reportDetails);
                        }*/
                        $(".full-info-text.details").text(returnedData[0].reportSummary);
                        $(".full-info-text.priority").text(priorityStringGenerator(returnedData[0].reportPriority));
                        $(".full-info-text.date").text(returnedData[0].reportDate);
                        $(".full-info-text.time").text(returnedData[0].reportTime);
                        //Admin-Set information changed below
                        $(".full-info-text.adminPriority").text(priorityStringGenerator(returnedData[0].admin_priority));
                        $(".full-info-text.duration").text("Will take approximately " + returnedData[0].duration + " hr(s) to complete");
                        $(".full-info-text.notes").text(returnedData[0].admin_notes);
                        //Final Color-coding
                        detailedReport_ColorCoding(returnedData[0].reportPriority,"priority");
                        detailedReport_ColorCoding(returnedData[0].admin_priority,"adminPriority");
                        //Disable Resolution/Editing when the report has been marked for deletion
                        if(returnedData[0].markedForDeletion==1){
                            $(".resolutionTools").hide();
                            $("#edit_issue").hide();
                        }else{
                            $(".resolutionTools").show();
                            $("#edit_issue").show();
                        }
                        //When the report is resolved turn off editing and disable the resolve button
                        if(returnedData[0].resolved==1){
                            $("#resolve_issue").text("Resolved");
                            $("#resolve_issue").prop('disabled',true);
                            $("#edit_issue").hide();
                            $("#resolved_icon").show();
                            $("#resolve_issue").parent().parent().find(".modal-body").addClass("greyOut");
                        }else{
                            $("#resolve_issue").text("Resolve");
                            $("#resolve_issue").prop('disabled',false);
                            $("#edit_issue").show();
                            $("#resolved_icon").hide();
                            $("#resolve_issue").parent().parent().find(".modal-body").removeClass("greyOut");
                        }
                        //Set date resolved in the date message box
                        if(returnedData[0].dateResolved!=""){
                            $("#viewReport_dateMsg").text("on " + returnedData[0].dateResolved);
                        }else{
                            $("#viewReport_dateMsg").empty();
                            //Set date last edited in the date message box (if the report has been edited previously)
                            if(returnedData[0].dateEdited!=""){
                                $("#viewReport_dateMsg").text("Last edited on " + returnedData[0].dateEdited);
                            }else{
                                $("#viewReport_dateMsg").empty();
                            }
                        }
                        //Report Listing Changes
                        $(".main-panel").find("#"+id).find(".report-title").empty();
                        $(".main-panel").find("#"+id).find(".report-title").html(returnedData[0].reportSummary);
                        $(".main-panel").find("#"+id).find(".report-date").empty();
                        $(".main-panel").find("#"+id).find(".report-date").html(returnedData[0].reportDate);
                        $(".main-panel").find("#"+id).find(".report-duration").empty();
                        $(".main-panel").find("#"+id).find(".report-duration").html(returnedData[0].duration+" hr");
                        $(".main-panel").find("#"+id).find(".report-adminPriority").empty();
                        $(".main-panel").find("#"+id).find(".report-adminPriority").html(priorityFlagCodeGenerator(returnedData[0].admin_priority));
                    }
                });
                break;
            case 2:
                $(".main-panel").find("#"+id).find(".report-resolution").empty();
                $(".main-panel").find("#"+id).find(".report-resolution").html('<img src="assets/icons/checkmark.png"/>');
                break;
            default:
                //TEST: console msg
                console.log("Invalid AJAX refresh request");
                break;
    }
}
//-----------Populate Reports------------
//function cacheReports(callback){
//    var request = {reqType:13};
//    ajaxRequest("databaseButler.php","text",request,function(returnedData){
//        console.log(returnedData);
//        /*if(returnedData=="ok"){
//            console.log(returnedData);
//            callback();
//        }else{
//            console.log("Failed to cache reports. Check back-end.");
//            console.log(returnedData);
//        }*/
//    });
//}
//function testFetch(){
//    ajaxRequest("databaseButler.php?reqType="+14,"text",null,function(returnedData){
//        console.log(returnedData);
//    });
//    console.log("Callback works! Wait for ajax log next.");
//}
//function populateReportList(){
//    cacheReports(function(){
//        testFetch();
//    });
//}
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
//-------------Confirmation Popup-------------
var confirm_response;
function confirmation_init(obj,callback){
    $(obj).attr("data-toggle","modal");
    $(obj).attr("data-target","#confirmation");
    $("#confirm_yes").on('click',function(){
        confirm_response = 1;
        $("#confirm_close").trigger('click');
        callback(confirm_response);
    });
    $("#confirm_no").on('click',function(){
        confirm_response = 0;
        $("#confirm_close").trigger('click');
        callback(confirm_response);
    });
}
//-------------Database------------
//ID Hashing Function
function hash(){
    //100 reports a day, frequency is per minute
    var now = new Date();
    var curr = now.getDate().toString().split("").reverse().join("")[0]+""+now.getMonth().toString().split("").reverse().join("")[0];
    return curr + "" + ($("#report-listing > tr").length+1) + "" + Math.floor((Math.random()*10)+1);
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
        regX = /((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4})(\s?(x\d{5})))|((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4}))|(x?\d{5})/g; //Standard US/Canadian Phone # along with an optional 5 digit extension beginning with an 'x' appended to the end /w or /wo a space
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
            }
        }
        //Summary Validation
        regX = /.{5,}/g;
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
                    newReport_valid = true;
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
        var fields = ["#editReport_summary","#editReport_name","#editReport_phone","#editReport_email","#editReport_date","#editReport_time"];
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
        //Summary validation
        regX = /.{5,}/g;
        validationColors($("#editReport_summary").val(),regX,"#editReport_summary",1,1);
        //Name validation
        var regX = /^[a-z|A-Z|\s*]+$/i; //First name and/or last name (with a space inbetween) No numbers or symbols allowed
        validationColors($("#editReport_name").val(),regX,"#editReport_name",1,1);
        //Phone Validation
        regX = /((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4})(\s?(x\d{5})))|((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4}))|(x?\d{5})/g; //Standard US/Canadian Phone # along with an optional 5 digit extension beginning with an 'x' appended to the end /w or /wo a space
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
        $("#newReport_infoMsg").html("Hover over submit to check your form.&nbsp&nbsp");
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
    //$("#newReport_details").val('');
    $("#newReport_extension").val('');
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
    //Disable authentication gate
    //$("#newReport_authKey").prop('disabled',true);
    //$("#newReport_authKey").prop('placeholder','not required when signed in');
}
//New Report Compilation
function newReport_compilation(){
    //Collect all the client-entered values and make a JSON string out of it. Also include the request type at the start.
    //CHANGES: removed det:$("#newReport_details").val()
    var formData = {reqType:2,id:hash(),na:$("#newReport_name").val(),ph:$("#newReport_phone").val()+"  x"+$("#newReport_extension").val(),em:$("#newReport_email").val(),dep:$("#newReport_department").val(),req:$("#newReport_requestCategory").val(),cus:$("#newReport_otherRequest").val(),summ:$("#newReport_summary").val(),pri:priorityNumberGenerator($("input[type='radio'][name='priority']:checked").val()),dat:$("#newReport_date").val(),tim:$("#newReport_time").val()};
    //TEST: progress bar
    progressBar_modify("#newReport_progress",15);
    return formData;
}
//New Report submit
function newReport_formSubmission(){
    $("#newReport_submit").on('click',function(){
        //Check the form if everything is valid (progress bar is the 3rd & final validation step)
        finalValidationCheck("newReport");
        if(newReport_valid==true){
            //The valid boolean should be set to false immediately
            newReport_valid = false;
            //Disable the submit button to prevent multiple submissions
            $("#newReport_submit").attr('disabled',true);
            //Compile the form, add to database, update progress bar and make an entry in the table
            var formData = newReport_compilation();
            //TEST: console msg (returned JSON data)
            //console.log(formData);
            //NOTE: Review AJAX insert into database
            ajaxRequest("databaseButler.php", "text", formData, function(returnedData){
                //Test: progress bar
                progressBar_modify("#newReport_progress",25);
                if(returnedData=="ok"){
                    //Inform the user that the form is valid
                    newReport_message("Looks great! Thanks!");
                    //Test: progress bar
                    progressBar_modify("#newReport_progress",10);
                    progressBar_reset("#newReport_progress");
                    //Clear form and close AFTER 3.5s IF progress bar is FULL
                    if($("#newReport_progress").attr('aria-valuenow')>=100){
                        setTimeout(function(){
                            $("#newReport_clear").trigger("click");
                            $("#newReport_close").trigger("click");
                        },3500);
                        //TODO: AJAX refresh
                        setTimeout(function(){
                            location.reload();
                        },3500);
                    }else{
                        //Inform the user that something went wrong
                        newReport_message("Submission failed! :( Something went wrong, try again later.");
                        //TEST: console msg
                        console.log("Submission stopped at: " + $("#newReport_progress").attr('aria-valuenow') + "%.");
                        //TEST: Clears
                        progressBar_reset("#newReport_progress");
                        //Re-enable the submit button on submission failure
                        $("#newReport_submit").attr('disabled',false);
                    }
                }else{
                    //TEST: console msg
                    console.log("Submission failed. Database query error.");
                    //Inform the user that something went wrong
                    newReport_message("Submission failed! :( Something went wrong, try again later.");
                    //Re-enable the submit button on submission failure
                    $("#newReport_submit").attr('disabled',false);
                    //TEST: Clears
                    progressBar_reset("#newReport_progress");
                }
            });
        }else{
            //Inform the user that the form has some invalid fields
            newReport_message("Correct the fields in <b>red</b> first!");
            //TEST: Clears
            progressBar_reset("#newReport_progress");
            //The valid boolean should be set to false immediately
            newReport_valid = false;
            //Re-enable the submit button on submission failure
            $("#newReport_submit").attr('disabled',false);
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
function editReport_compilation(id){
    //Collect the edit form data
    if($("input[type='radio'][name='adminPriority']:checked").val()!=undefined){
        var formData = {reqType:4,id:id,summ:$("#editReport_summary").val(),na:$("#editReport_name").val(),ph:$("#editReport_phone").val(),em:$("#editReport_email").val(),dat:$("#editReport_date").val(),tim:$("#editReport_time").val(),admPr:priorityNumberGenerator($("input[type='radio'][name='adminPriority']:checked").val()),dur:$("#editReport_durationSlider").val(),nte:$("#editReport_notes").val()};
    }else{
       var formData = {reqType:4,id:id,summ:$("#editReport_summary").val(),na:$("#editReport_name").val(),ph:$("#editReport_phone").val(),em:$("#editReport_email").val(),dat:$("#editReport_date").val(),tim:$("#editReport_time").val(),admPr:0,dur:$("#editReport_durationSlider").val(),nte:$("#editReport_notes").val()};
    }
    return formData;
}
function viewEditForm(){
    $("#edit_issue").on("click",function(){
        //TEST: Get the ID of the report to be edited
        //var temp_index = database_indexReturn($(this).parent().parent().attr("id"));
        var rep_ID = $(this).parent().parent().attr('id');
        var request = {reqType:3,queryID:rep_ID};
        //NOTE: Review AJAX edit form
        ajaxRequest("databaseButler.php", "json", request, function(returnedData){
            if(returnedData[0].error=="fail"){
                console.log("Populating edit report failed. Check Database Query.");
            }else{
                //TEST: console msg
                console.log("Edit form populated");
                //Fill the client part of the edit form with available info
                $("#editReport_summary").val(returnedData[0].reportSummary);
                $("#editReport_name").val(returnedData[0].reportName);
                $("#editReport_phone").val(returnedData[0].reportPhone);
                $("#editReport_email").val(returnedData[0].reportEmail);
                $("#editReport_date").val(returnedData[0].reportDate);
                $("#editReport_time").val(returnedData[0].reportTime);
                if(returnedData[0].admin_priority==0){
                    $("input[type='radio'][name='adminPriority'][id='admIna']").prop("checked",true);
                }else if(returnedData[0].admin_priority==1){
                    $("input[type='radio'][name='adminPriority'][id='admLow']").prop("checked",true);
                }else if(returnedData[0].admin_priority==2){
                    $("input[type='radio'][name='adminPriority'][id='admMed']").prop("checked",true);
                }else if(returnedData[0].admin_priority==3){
                $("input[type='radio'][name='adminPriority'][id='admHig']").prop("checked",true);
                }else{
                    //TEST: console.msg
                    console.log("Reading invalid admin_priority string. (View Edit Form)");
                }
                $("#editReport_notes").val(returnedData[0].admin_notes);
                //Setup the duration slider
                $("#editReport_durationSlider").noUiSlider({
                    start: returnedData[0].duration,
                    step: 1,
                    connect: "lower",
                    orientation: "horizontal",
                    range: {
                        'min': 1,
                        'max': 7
                    },
                    format: wNumb({
                        decimals: 0
                    })
                });
                //Show the initial value
                $("#duration_tooltip").text(returnedData[0].duration + " hr(s)");
                //Update the value in the label so the user knows what value the slider is at
                $("#editReport_durationSlider").on('slide',function(){
                    $("#duration_tooltip").text($(this).val()+" hr(s)");
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
                editReport_message("Editing report...",2500);
            }
        });
        //NOTE: code from closeEditForm onwards was previously here.
    });
}
function closeEditForm(op){
    if(op){
        //Destroy the slider object
        $("#editReport_durationSlider")[0].destroy();
    }
    //Hide the edit report form
    $("#editReport").hide();
    //Hide the save tools
    $(".saveTools").hide();
    //Enable the edit button
    $("#edit_issue").prop('disabled',false);
    //Enable the resolution tools
    $(".resolutionTools").prop('disabled',false);
}
//Saving Edits
$("#editReport_save").on("click",function(){
    //Validate client section
    finalValidationCheck("editReport");
    if(editReport_valid==true){
        //Collect edit form data
        var rep_ID = $(this).parent().parent().attr("id");
        var formData = editReport_compilation(rep_ID);
        //NOTE: Review AJAX save edit form
        ajaxRequest("databaseButler.php", "text", formData, function(returnedData){
            if(returnedData=="ok"){
                //Close edit form & related
                closeEditForm(true);
                //Inform the user
                editReport_message("Changes saved!",3500);
                //NOTE: Review AJAX refresh
                ajaxRefresh(1,rep_ID);
                /*setTimeout(function(){
                        location.reload();
                },3500);*/
                //Reset the boolean
                editReport_valid = false;
            }else{
                //TEST: console msg
                console.log("Edit form could not be submitted! SQL query error.");
            }
        });
    }else{
        //Inform the user that the form has some invalid fields
        editReport_message("Correct the fields in <b>red</b> first!",3500);
    }
});
$("#editReport_discard").on("click",function(){
    //Close edit form & related
    closeEditForm(true);
    //Clear the edit form //TODO: probably unnecessary since hitting edit loads info while replacing existing content
    //Inform the user
    editReport_message("Changes discarded!",5000);
});
//-----------------Reports Table Entries Tools/Utilities--------------
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
        //NOTE: review AJAX update
        ajaxRequest("databaseButler.php?reqType="+1+"&reqParam="+0+"&queryID="+id, "text", null, function(returnedData){
            if(returnedData=="ok"){
                //TEST: Console msg
                console.log("Report #"+id+" unmarked.");
                //Remove the greyOut
                $("#"+id).removeClass("greyOut");
                //Show the delete button again (after a delay)
                setTimeout(function() {
                    $(deleteButton).show();
                }, 3000);
            }else{
                //TEST: console msg
                console.log("Report restoration failed.");
            }
        });
    });
}
function reportDeletion(){
    //Allow report deletion
    $(".delete").on("click",function(){
        var obj = $(this);
        var reportID = $(this).parent().parent().attr('id');
        //var toggle = 0;
        //Mark report for deletion
        //NOTE: review AJAX update
        ajaxRequest("databaseButler.php?reqType="+1+"&reqParam="+1+"&queryID="+reportID, "text", null, function(returnedData){
            if(returnedData=="ok"){
                //TEST: Console msg
                console.log("Report #" + reportID + " marked for deletion.");
                //Remove report listing (after a delay)
                $("#"+reportID).addClass("greyOut");
                $(obj).css('display','none');
                //Add a visual timer
                $(obj).parent().append('<div class="timer" id="timer'+ reportID + '"></div>');
                //Place the timer in the element inserted above
                newTimer("#timer"+reportID,60);
                //Delete the report from the database and the display after the time
                var deleteTimeout = setTimeout(function(){
                    //Remove the table entry
                    obj.parent().parent().remove();
                    //Delete the report data from the database
                    //TODO: AJAX deletion
                    /*ajaxRequest("databaseButler.php?reqType="+6+"&queryID="+reportID,"text",null,function(returnedData){
                        if(returnedData=="ok"){
                            //TEST: console msg
                            console.log("Report #"+reportID+" deleted.");
                            $(".main-panel").find("#"+reportID).remove();
                        }else{
                            //TEST: console msg
                            console.log("Deleting report #"+reportID+" failed.");
                        }
                    });*/
                },60000);
                //Show the restoration button
                $("#restore"+reportID).show();
                //Allow restoration
                reportRestoration(reportID,obj,deleteTimeout);
            }else{
                //TEST: Console msg
                console.log("Report deletion failed.");
            }
        });
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
        //**Check detailedReportBuilder below for additional deletion utility**
    });
}
//Detailed Report View Generator (Asynchronous elements inside)
function detailedReportBuilder(){
    //Detailed Report Modal View for View Button
    $(".report-tools .view").attr("data-toggle","modal");
    $(".report-tools .view").attr("data-target","#full-info");
    //Customize all fields for the exact report that was clicked
    $(".view").on("click", function (){
        //Remove carry-over edit form data
        if($("#editReport").css('display')=='block'){
            closeEditForm(true);
        }else if($("#editReport").css('display')=='none'){
            closeEditForm(false);
        }
        //Acquire the index of the report entry
        var rep_ID = $(this).parent().parent().attr('id');
        //Set the ID of the modal container to the report ID (USED BY THE EDITING REPORT ENGINE)
        $(".modal-content").attr("id",rep_ID);
        //NOTE: Review following detailed view AJAX code
        ajaxRequest("databaseButler.php?reqType="+0+"&queryID="+rep_ID, "json", null, function(returnedData){
            if(returnedData[0].error=="fail"){
                console.log("Populating detailed view failed. Check Database Query.");
            }else{
                //CHANGES: select 60 character substring out of summary
                $("#full-info-title").text("#" + rep_ID + " " + returnedData[0].reportSummary.substr(0,60) + "...");
                $(".full-info-text.name").text(returnedData[0].reportName);
                $(".full-info-text.phone").text(returnedData[0].reportPhone);
                $(".full-info-text.email").text(returnedData[0].reportEmail);
                $(".full-info-text.department").text(returnedData[0].reportDepartment);
                if(returnedData[0].reportRequest=="Other"){
                    $(".full-info-text.request").text(returnedData[0].reportCustomRequest);
                }else{
                    $(".full-info-text.request").text(returnedData[0].reportRequest);
                }
                //CHANGES: removed reportDetails and populated field with summary
                /*if(returnedData[0].reportDetails!=null){
                    $(".full-info-text.details").text(returnedData[0].reportDetails);
                }*/
                $(".full-info-text.details").text(returnedData[0].reportSummary);
                $(".full-info-text.priority").text(priorityStringGenerator(returnedData[0].reportPriority));
                $(".full-info-text.date").text(returnedData[0].reportDate);
                $(".full-info-text.time").text(returnedData[0].reportTime);
                //Admin-Set information changed below
                $(".full-info-text.adminPriority").text(priorityStringGenerator(returnedData[0].admin_priority));
                $(".full-info-text.duration").text("Will take approximately " + returnedData[0].duration + " hr(s) to complete");
                $(".full-info-text.notes").text(returnedData[0].admin_notes);
                //Final Color-coding
                detailedReport_ColorCoding(returnedData[0].reportPriority,"priority");
                detailedReport_ColorCoding(returnedData[0].admin_priority,"adminPriority");
                //Disable Resolution/Editing when the report has been marked for deletion
                if(returnedData[0].markedForDeletion==1){
                    $(".resolutionTools").hide();
                    $("#edit_issue").hide();
                }else{
                    $(".resolutionTools").show();
                    $("#edit_issue").show();
                }
                //When the report is resolved turn off editing and disable the resolve button
                if(returnedData[0].resolved==1){
                    $("#resolve_issue").text("Resolved");
                    $("#resolve_issue").prop('disabled',true);
                    $("#edit_issue").hide();
                    $("#resolved_icon").show();
                    $("#resolve_issue").parent().parent().find(".modal-body").addClass("greyOut");
                }else{
                    $("#resolve_issue").text("Resolve");
                    $("#resolve_issue").prop('disabled',false);
                    $("#edit_issue").show();
                    $("#resolved_icon").hide();
                    $("#resolve_issue").parent().parent().find(".modal-body").removeClass("greyOut");
                }
                //Set date resolved in the date message box (if the report has been resolved)
                if(returnedData[0].dateResolved!=""){
                    $("#viewReport_dateMsg").text("on " + returnedData[0].dateResolved);
                }else{
                    $("#viewReport_dateMsg").empty();
                    //Set date last edited in the date message box (if the report has been edited previously)
                    if(returnedData[0].dateEdited!=""){
                        $("#viewReport_dateMsg").text("Last edited on " + returnedData[0].dateEdited);
                    }else{
                        $("#viewReport_dateMsg").empty();
                    }
                }
                //Update tag
                ajaxTagUpdater(rep_ID);
            }
        });
    });
}
//Detailed Report Color-coding
function detailedReport_ColorCoding(value,field){
    if(field=="priority"){
        switch(value){
            case 1:
                $(".priority_container").css("background-color","#7AC74F");
                break;
            case 2:
                $(".priority_container").css("background-color","#E8C571");
                break;
            case 3:
                $(".priority_container").css("background-color","salmon");
                break;
            default:
                $(".priority_container").css({
                    "background-color":"#2B2D42",
                    "color":"white"
                });
                break;
        }
    }else if(field=="adminPriority"){
        switch(value){
            case 0:
                $(".adminPriority_container").css("background-color","rgba(205, 205, 205, 0.5)");
                break;
            case 1:
                $(".adminPriority_container").css("background-color","rgba(122, 199, 79, 0.5)");
                break;
            case 2:
                $(".adminPriority_container").css("background-color","rgba(232, 197, 113, 0.5)");
                break;
            case 3:
                $(".adminPriority_container").css("background-color","rgba(250, 128, 114, 0.5)");
                break;
            default:
                $(".adminPriority_container").css({
                    "background-color":"#2B2D42",
                    "color":"white"
                });
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
    }
}
function progressBar_reset(elem){
    //RESET after 3s
    setTimeout(function(){
      $(elem).css('width',0+'%');
        $(elem).attr('aria-valuenow',0);
    },3000);
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
function priorityFlagCodeGenerator(val){
    switch(val){
        case 0:
            return '<img src="assets/icons/grey-flag.png"/>';
        case 1:
            return '<img src="assets/icons/green-flag.png"/>';
        case 2:
            return '<img src="assets/icons/orange-flag.png"/>';
        case 3:
            return '<img src="assets/icons/red-flag.png"/>';
        default:
            return '<img src="assets/icons/hourglass.png"/>';
    }
}
function resolutionFlagCodeGenerator(val){
    switch(val){
        case 0:
            return '<img src="assets/icons/close.png"/>';
        case 1:
            return '<img src="assets/icons/checkmark.png"/>';
        default:
            return '<img src="assets/icons/hourglass.png"/>';
    }
}
function priorityNumberGenerator(val){
    switch(val){
        case "Inactive":
            return 0;
        case "Low":
            return 1;
        case "Medium":
            return 2;
        case "High":
            return 3;
        default:
            return 4;
    }
}
function priorityStringGenerator(val){
    switch(val){
        case 0:
            return "Inactive";
        case 1:
            return "Low";
        case 2:
            return "Medium";
        case 3:
            return "High";
        default:
            return "Pending";
    }
}
function tagCodeGenerator(val){
    switch(val){
        case 0:
            return '';
        case 1:
            return '<img src="assets/icons/new.png"/>';
        case 2:
            return '<img src="assets/icons/targetSoon.png"/>';
        case 3:
            return '<img src="assets/icons/targetNow.png"/>';
        case 4:
            return '<img src="assets/icons/sad.png"/>';
        case 5:
            return '<img src="assets/icons/view.png"/>';
        default:
            return '<img src="assets/icons/bomb.png"/>';
    }
}
//---------------Updating Tags-------------------
//Manual tag refresh
$(".main-panel th:nth-of-type(4)").on('click',function(){
    for(var i=0;i<$("#report-listing > tr").length;i++){
        //console.log($("#report-listing > tr:eq("+i+")").attr('id'));
        ajaxTagUpdater($("#report-listing > tr:eq("+i+")").attr('id'));
    }
});
//Periodic Report Checker/Updater
function tagUpdater(){
    var alg = setInterval(function(){
        for(var i=0;i<$("#report-listing > tr").length;i++){
            //console.log($("#report-listing > tr:eq("+i+")").attr('id'));
            ajaxTagUpdater($("#report-listing > tr:eq("+i+")").attr('id'));
        }
    },240000);
}
//AJAX Updater (Single Report)
function ajaxTagUpdater(id){
    ajaxRequest("databaseButler.php?reqType="+7+"&queryID="+id,"json",null,function(returnedData){
        if(returnedData[0].error=="fail"){
            console.log("Updating tag failed. Check Database Query.");
        }else{
            $(".main-panel").find("#"+id).find(".report-tag").empty();
            $(".main-panel").find("#"+id).find(".report-tag").html(tagCodeGenerator(returnedData[0].tag));
        }
    });
}
//---------------Report Resolution---------------
$("#resolve_issue").click(function(){
    var rep_ID = $(this).parent().parent().attr('id');
    var obj = $(this);
    //Detailed Report Modal View for Resolve button inside form
    confirmation_init("#resolve_issue",function(response){
        if(response==0){
            $("#confirm_close").trigger('click');
        }else{
            ajaxRequest("databaseButler.php?reqType="+5+"&reqParam="+1+"&queryID="+rep_ID,"text",null,function(returnedData){
                if(returnedData=="ok"){
                    $(obj).text("Resolved");
                    $(obj).prop('disabled',true);
                    $("#edit_issue").hide();
                    $("#resolved_icon").show();
                    $(obj).parent().parent().find(".modal-body").addClass("greyOut");
                    //Refresh anything on the app as necessary without the user having to reload the page
                    ajaxRefresh(2,rep_ID);
                    //TEST: console msg
                    console.log("Report #" + rep_ID + " resolved.");
                }else{
                    //TEST: console msg
                    console.log("Could not resolve #" + rep_ID);
                }
            });
        }
    });
});
//---------------Logout------------------
$("#logout").on('click',function(){
    //TEST: Console msg
    console.log("logging out...");
    $.post("index.html.php", {log_out: "1"});
    window.location.assign("landing-page.html.php");
});
//---------------Page Load---------------
// Functions to execute upon page load
$(document).ready(function(){
    //TEST: Fetch reports

    //NOTE: Report ID's are attached in the DOM in 2 places: each table row and the report detail modal, whenever it is opened.
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
    //TEST: Timepicker test
    $("#newReport_time").timepicker();
    //MOVED: Call to enable detailed report building
    detailedReportBuilder();
    //MOVED: Call to enable report deletion
    reportDeletion();
    //Tag Updating
    tagUpdater();
    //TEST: fetching reports
    populateReportList();
    //REFRESH
    /*var time = new Date().getTime();
     $(document.body).bind("click keypress", function(e) {
         time = new Date().getTime();
     });

     function refresh() {
         if(new Date().getTime() - time >= 7000)
             window.location.reload(true);
         else
             setTimeout(refresh, 30000);
     }

     setTimeout(refresh, 30000);*/
});
