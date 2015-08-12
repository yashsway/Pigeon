//Global Vars
var welcome_msg = "Hi! How can I help you today?";
var newReport_valid = false;
var totalReports = 0;
//------AJAX Requests-----------
var data = "";
function ajaxRequest(reqScript, returnDataType, reqData, callback){
    $.ajax({
        type: "POST",
        dataType: returnDataType,
        url: reqScript,
        data: reqData,
        success: function(data) {
            console.log("AJAX request success.");
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
//-------------Database------------
//ID Hashing Function
function hash(){
    //100 reports a day, frequency is per minute
    var now = new Date();
    var curr = now.getDate().toString().split("").reverse().join("")[0]+""+now.getMonth().toString().split("").reverse().join("")[0];
    return curr + "" + totalReports + "" + Math.floor((Math.random()*10)+1);
}
function getReportTotal(){
    var req = {reqType:9};
    ajaxRequest("databaseButler.php","json",req,function(returnedData){
        totalReports = returnedData['totalReports'];
        console.log("Total # of Requests: " + totalReports);
    });
}
//-----------Validation-----------------
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
        regX = /((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4})(\s?(x\d{5})))|((?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4}))|(x\d{5})/g; //Standard US/Canadian Phone # along with an optional 5 digit extension beginning with an 'x' appended to the end /w or /wo a space
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
function finalValidationCheck(){
    //rgb(255, 192, 203) = pink
    //rgb(203, 232, 150) = green
    var fields = ["#newReport_name","#newReport_phone","#newReport_email","#newReport_department","#newReport_requestCategory","#newReport_otherRequest","#newReport_summary","#newReport_priority","#newReport_date","#newReport_time"];
    for(var i=0;i<fields.length;i++){
        //TEST: Progress bar 50%
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
}
//------------New Report UI/Utilities/Tools---------------
function newReport_message(msg){
    //Display the message received
    $("#newReport_infoMsg").html(msg+"&nbsp&nbsp");
    //Set the default message back on after 5s
    setTimeout(function(){
        $("#newReport_infoMsg").html("All fields are required, except details.&nbsp&nbsp");
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
    //Collect all the client-entered values and make a JSON string out of it. Also include the request type at the start.
    var formData = {reqType:2,id:hash(),na:$("#newReport_name").val(),ph:$("#newReport_phone").val(),em:$("#newReport_email").val(),dep:$("#newReport_department").val(),req:$("#newReport_requestCategory").val(),cus:$("#newReport_otherRequest").val(),summ:$("#newReport_summary").val(),det:$("#newReport_details").val(),pri:priorityNumberGenerator($("input[type='radio'][name='priority']:checked").val()),dat:$("#newReport_date").val(),tim:$("#newReport_time").val()};
    //TEST: progress bar 65%
    progressBar_modify("#newReport_progress",15);
    //TEST: console msg (JSON data)
    console.log("Before Send: " + formData);
    return formData;
}
//New Report submit
function newReport_formSubmission(){
    $("#newReport_submit").on('click',function(){
        //Disable the submit button on submission
        $("#newReport_submit").attr('disabled',true);
        //Check the form if everything is valid (progress bar is the 3rd & final validation step)
        finalValidationCheck();
        if(newReport_valid==true){
            //The valid boolean should be set to false immediately
            newReport_valid = false;
            //Disable the submit button to prevent multiple submissions
            $("#newReport_submit").attr('disabled',true);
            //Compile the form, add to database, update progress bar and make an entry in the table
            var formData = newReport_compilation();
            //TEST: console msg (returned JSON data)
            console.log(formData);
            //NOTE: Review AJAX insert into database
            ajaxRequest("databaseButler.php", "text", formData, function(returnedData){
                //Test: progress bar 90%
                progressBar_modify("#newReport_progress",25);
                if(returnedData=="Query ok"){
                    //Inform the user that the form is valid
                    newReport_message("Looks great! Thanks!");
                    //Bind view & delete buttons //TODO: Automatically BIND view and delete on creation of new row
                    //detailedReportBuilder();
                    //reportDeletion();
                    //Test: progress bar 100%
                    progressBar_modify("#newReport_progress",10);
                    progressBar_reset("#newReport_progress");
                    //Clear form and close AFTER 3.5s IF progress bar is FULL
                    if($("#newReport_progress").attr('aria-valuenow')==100){
                        setTimeout(function(){
                            $("#newReport_clear").trigger("click");
                            $("#newReport_close").trigger("click");
                        },3500);
                        $("#welcome-icon").hide();
                        $("#landing-buttons-wrapper").hide();
                        $("#newReport-ticketNumber-wrapper").show();
                        $(".back").attr('id','fromSubmission');
                        $(".back").show();
                        //TEST: ticket # display
                        $("#newReport_ticketNumber").text(formData.id);
                        $("#help-text").html("<b>Write down</b> this number for future reference! You can use it to check the status of your request. <br/>We'll get back to you as soon as possible.");
                        $("#fromSubmission").on('click',function() {
                            $("#welcome-icon").show();
                            $("#newReport-ticketNumber-wrapper").hide();
                            $("#landing-buttons-wrapper").show();
                            $(".back").hide();
                            $("#help-text").text(welcome_msg);
                        });
                        //Re-enable the submit button AFTER submission
                        $("#newReport_submit").attr('disabled',false);
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
                    //TODO: AJAX refresh
                    /*setTimeout(function(){
                        location.reload();
                    },3500);*/
                }else{
                    //TEST: console msg
                    console.log("Submission failed. Database query error.");
                    //Inform the user that something went wrong
                    newReport_message("Submission failed! :( Something went wrong with the database, try again later.");
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
//----------------------------Controllers------------------------
function welcomeDisplayController(toggle){
    if(toggle){
        $("#welcome-icon").show();
        $("#landing-buttons-wrapper").show();
        $("#help-text").text(welcome_msg);
    }else{
        $("#welcome-icon").hide();
        $("#landing-buttons-wrapper").hide();
        $("#help-text").text('');
    }
}
function loginDisplayController(toggle){
    if(toggle){
        $("#login-wrapper").show();
        $("#login").show();
    }else{
        $("#user_name").val('');
        $("#pass_word").val('');
        $("#login-wrapper").hide();
        $("#login").hide();
    }
}
function checkDisplayController(toggle){
    if(toggle){
        $("#checkStatus-input-wrapper").show();
        $("#checkTicket").show();
        $("#userQuery").text('');
        $("#help-text").text('This number was given to you when you filed a report on Pigeon.');
    }else{
        $("#checkStatus-input-wrapper").hide();
        $("#checkStatus-display-wrapper").hide();
        $("#userQuery").text('');
        $("#checkTicket").hide();
        $("#checkStatus-statusText").removeClass("animated bounce");
    }
}
function backController(toggle,obj){
    if(toggle){
        $(".back").attr('id',obj);
        $(".back").show();
    }else{
        $(".back").hide();
    }
}
//----------------------------Check Ticket-----------------------
$("#checkTicket_trigger").on('click',function() {
    backController(true,"fromCheck");
    welcomeDisplayController(false);
    checkDisplayController(true);
    $("#checkStatus-statusText").text("Searching for request. Hang tight.");
    $("#fromCheck").on('click',function() {
        backController(false,"fromCheck");
        checkDisplayController(false);
        welcomeDisplayController(true);
    });
});
$("#checkTicket").on('click',function(){
    var qry = $("#userQuery").val();
    var formData = {reqType:8,ticket:qry};
    ajaxRequest("databaseButler.php","text",formData,function(returnedData){
        $("#checkStatus-statusText").text("Status: " + returnedData);
        $("#checkStatus-statusText").addClass("animated bounce");
        $("#checkStatus-display-wrapper").show();
        $("#checkStatus-display-wrapper").one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            $("#checkStatus-statusText").removeClass("animated bounce");
        });
    });
});
//----------------------------Login------------------------------
$("#login_trigger").on('click',function() {
    $("#newReport-ticketNumber-wrapper").hide();
    welcomeDisplayController(false);
    $("#help-text").text("Contact the administrator if you forgot your password.");
    loginDisplayController(true);
    $(".back").css('margin-left','44%');
    backController(true,'fromLogin');
    $("#fromLogin").on('click',function() {
        backController(false,'fromLogin');
        welcomeDisplayController(true);
        loginDisplayController(false);
    });
});
$("#login").on('click',function(){
    var usr = $("#user_name").val();
    var pass = $("#pass_word").val();
    var formData = {user_name:usr,pass_word:pass};
    ajaxRequest("login.php", "text", formData, function(returnedData){
        if(returnedData=="fail"){
            $("#user_name").addClass('animated flash');
            $("#pass_word").addClass('animated flash');
            $("#help-text").text("Incorrect email/password!");
            setTimeout(function(){
                $("#help-text").text("Contact the administrator if you forgot your password.");
                $("#user_name").removeClass('animated flash');
                $("#pass_word").removeClass('animated flash');
            },3500);
        }else if(returnedData=="success"){
            window.location.assign("index.html.php");
        }
    });
});
//----------------------------Page Load--------------------------
$(document).ready(function(){
    getReportTotal();
    //Login when the enter key is pressed in the password input
    $('#pass_word').bind('keypress', function(e) {
        var code = e.keyCode || e.which;
        if(code == 13) { //Enter keycode
           $("#login").trigger('click');
        }
    });
    //New Report Modal View for Report button
    $("#newReport_trigger").attr("data-toggle","modal");
    $("#newReport_trigger").attr("data-target","#file-new-report");
    //Enable new reports
    newReport_engine();
    //Datedroppers
    $("#newReport_date").dateDropper();
    //Enable tooltips
    $('[data-toggle="tooltip"]').tooltip();
    //Other UI stuff
    $("#newReport-ticketNumber-wrapper").hide();
    loginDisplayController(false);
    checkDisplayController(false);
    welcomeDisplayController(true);
});
