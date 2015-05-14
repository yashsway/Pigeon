//Database to hold report objects
var reports = new Array();
var ID, name, phone, email, department, request, custom_request, summary, details, priority, date, time, duration, admin_priority;
//TEST: all of the following arrays are test report data
var test_names = ['Peter Gregory','Rade Kuruc','Jaimie Dickson','Marta Prancho','Mia Cai','Goranka Gaechesca'];
var test_phones = ['905-525-9140','905-525-9140','905-525-9140','905-525-9140','905-525-9140','905-525-9140'];
var test_emails = ['gregp@mcmaster.ca','kurucr@mcmaster.ca','jdickso@mcmaster.ca','mprancho@mcmaster.ca','caimi@mcmaster.ca','gacesag@mcmaster.ca'];
var test_departments = ['Residence Admissions','Residence Admissions','Residence Admissions','Residence Admissions','Residence Admissions','Residence Admissions'];
var test_requests = ['Other','Other','Other','Other','Other','Other'];
var test_customs= ['test','test','test','test','test','test'];
var test_summaries = ['Sample Report','Sample Report','Sample Report','Sample Report','Sample Report','Sample Report'];
var test_details = ['test','test','test','test','test','test'];
var test_priorities = [0,1,1,0,2,2];
var test_dates = ['04/01/2015','04/01/2015','04/01/2015','04/01/2015','04/01/2015','04/01/2015'];
var test_times = ['8:30 AM','8:30 AM','8:30 AM','8:30 AM','8:30 AM','8:30 AM'];

//Report ADT
function report(id,na,ph,em,dep,req,cus,summ,det,pri,dat,tim,dur,adm){
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
    this.duration = "-";
    this.admin_priority = "-";
}

//Pushes report objects into database (Local arrays currently, switch to SQL later!)
function databaseBuilder(){
    for(var i=0;i<6;i++){
        reports.push(new report(hash(i),test_names[i],test_phones[i],test_emails[i],test_departments[i],test_requests[i],test_customs[i],test_summaries[i],test_details[i],test_priorities[i],test_dates[i],test_times[i]));
    }
    //TEST: Console msg
    console.log("Database Build Complete!"); //TEST
}

//New Report Validation
function newReport_validation(){
    function validationColors(val,regEx,obj,mode){
        if(mode==0){
            if(regEx==false){
                $(obj).parent().css('background-color','pink');
            }else if(regEx==true){
                $(obj).parent().css('background-color','#CBE896');
            }
        }
        else if(regEx.test(val)){
            $(obj).parent().css('background-color','#CBE896');
        }else{
            $(obj).parent().css('background-color','pink');
        }
    }
    $("#newReport_name").focusout(function(){
        var emailRegx = /^[a-z|A-Z|\s*]+$/i; //First name and/or last name (with a space inbetween) No numbers or symbols allowed
        validationColors($(this).val(),emailRegx,this,1);
    });
    $("#newReport_phone").focusout(function(){
        var phoneRegx = /(?:\d{1}\s)?\(?(\d{3})\)?-?\s?(\d{3})-?\s?(\d{4})\s?(x\d{5})/g; //Standard US/Canadian Phone # along with 5 digit extension beginning with an 'x' appended to the end /w or /wo a space
        validationColors($(this).val(),phoneRegx,this,1);
    });
    $("#newReport_email").focusout(function(){
        var emailRegx = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/g; //Standard email.
        validationColors($(this).val(),emailRegx,this,1);
    });
    $("#newReport_department").mouseleave(function(){
        if($(this).val()=="blank" || $(this).text()==""){
            validationColors($(this).val(),false,this,0);
        }else{
            validationColors($(this).val(),true,this,0);
        }
    });
    $("#newReport_requestCategory").mouseleave(function(){
        var v = $(this).val();
        if(v=="blank"){
            validationColors($(this).val(),false,this,0);
        }else{
            validationColors($(this).val(),true,this,0);
        }
    });
    $("#newReport_otherRequest").mouseover(function(){
        var otherRegx = /.{5,}/g;
        if($("#newReport_requestCategory").val()=="other_request"){
            if($(this).text()==""){
                validationColors($(this).val(),otherRegx,this,1);
            }else{
                validationColors($(this).val(),otherRegx,this,1); 
            }
        }
    });
    $("#newReport_summary").focusout(function(){
        var summRegx = /.{5,}/g; //Standard email.
        validationColors($(this).val(),summRegx,this,1);
    });
    $("#newReport_meetingTime").mouseleave(function(){
       var datRegx = /.{10,}/g;
       var timRegx = /(\d{1,2}:?\s?\d{2,2}\s?(AM|am|PM|pm))|(anytime)/g;
       validationColors($("#newReport_date").val(),datRegx,"#newReport_date",1);
       validationColors($("#newReport_time").val(),timRegx,"#newReport_time",1);
    });
    /*$("#newReport_submit").mouseover(function () {
        if ($("input[name='priority']:checked").val()) {
            validationColors($("#newReport_priority").val(),true,"#newReport_priority",0);
        }else if(!$("input[name='priority']:checked").val()){
            validationColors($("#newReport_priority").val(),false,"#newReport_priority",0);
        }
    });*/
}
//New Report Compilation
function newReport_compilation(){
    
}

//Table row builder
function rowBuilder(){
    //var temp_priority;
    //Add an entry in the panel for each report
    for(var i=0;i<reports.length;i++){
        //Determine priority in a client-understandable form
        //temp_priority = priorityString(reports[i].priority);
        //Report ID is also the ID of the table row.
        $(".main-panel > tbody").append('<tr id="' + reports[i].ID + '"><td class="report-elements report-id">' + reports[i].ID + '</td><td class="report-elements report-title">' + reports[i].summary + '</td><td class="report-elements report-date">' + reports[i].date + '</td><td class="report-elements report-duration">' + reports[i].duration + '</td><td class="report-elements report-urgency">' + priorityFlagCodeGenerator(reports[i].priority) + '</td><td class="report-elements report-tools"><button class="btn btn-default view">View</button>&nbsp&nbsp<button class="btn btn-danger delete">Delete</button></td></tr>');
    }
    //Build the detailed view
    detailedReportBuilder();
    //Detailed Report Modal View for View Button
    $(".report-tools .view").attr("data-toggle","modal");
    $(".report-tools .view").attr("data-target","#full-info");
    //Allow report deletion
    $(".delete").on("click",function(){
        var obj = $(this);
        var reportID = $(this).parent().parent().attr('id');
        //var reportIndex = reportID.charAt(0); //The first character is the index # (check hash function)
        //TEST: Console msg
        console.log("Report #" + reportID + " marked for deletion.");
        //Remove report listing (after a delay)
        $("#"+reportID).addClass("greyOut");
        $(obj).css('display','none');
        //Add a visual timer
        $(obj).parent().append('<div class="timer" id="timer'+ reportID + '"></div>');
        timer("#timer"+reportID,20);
        setTimeout(function(){ 
            obj.parent().parent().remove();
            //Delete the report data from the database
            database_dataDeleter(reportID);
        }, 20000);
    });
}

function timer(elem,time){
    $(elem).pietimer({
        seconds: time,
        color: 'rgba(0, 0, 0, 0.8)',
        height: 25,
        width: 25
    });
    $(elem).pietimer('start');
}

function database_dataDeleter(id){
   reports.splice(database_indexReturn(id),1);
   //TEST:
   console.log("Report #" + id + " deleted.");
}
function database_indexReturn(id){
    for(var i=0;i<reports.length;i++){
        if(id==reports[i].ID){
            return i;
        }
    }
}

//Detailed Report View Generator (Asynchronous elements inside)
function detailedReportBuilder(){
    //Customize all fields for the exact report that was clicked
    $(".view").on("click", function (){
        //Acquire the index of the report entry
        var query_ID = $(this).parent().parent().attr('id');
        var temp_index = database_indexReturn(query_ID);
        //Acquire the index of the entry (table row method - UNRELIABLE)
        //var temp_index = $(this).parent().parent().attr('id');
        $("#full-info-title").text("#" + reports[temp_index].ID + " " + reports[temp_index].summary);
        $(".full-info-text.name").text(reports[temp_index].name);
        $(".full-info-text.phone").text(reports[temp_index].phone);
        $(".full-info-text.email").text(reports[temp_index].email);
        $(".full-info-text.department").text(reports[temp_index].department);
        $(".full-info-text.request").text(reports[temp_index].request);
        $(".full-info-text.date").text(reports[temp_index].date);
        $(".full-info-text.details").text(reports[temp_index].details);
        $(".full-info-text.priority").text(priorityString(reports[temp_index].priority)); //Priority variable set above in case statement
        $(".full-info-text.time").text(reports[temp_index].time); 
        //Admin-Set information changed below
        //Final Color-coding
        detailedReport_ColorCoding(reports[temp_index].priority,"priority");
    });
}

//Priority Flag HTML generator
function priorityFlagCodeGenerator(value){
    switch(value){
        case 0:
            return '<img src="assets/icons/green-flag.png"/>';
        case 1:
            return '<img src="assets/icons/orange-flag.png"/>';
        case 2:
            return '<img src="assets/icons/red-flag.png"/>';
        default:
            return '<img src="assets/icons/risk.png"/>';
    }
}

//Detailed Report Color-coding
function detailedReport_ColorCoding(value,field){
    if(field=="priority"){
        switch(value){
            case 0:
                $(".priority_container").css("background-color","#7AC74F");
                break;
            case 1:
                $(".priority_container").css("background-color","#E8C571");
                break;
            case 2:
                $(".priority_container").css("background-color","salmon");
                break;
            default:
                break;
        }
    }
}

//Priority String Setter
function priorityString(value){
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
}

//ID Hashing Function
function hash(n){
    return n + "" + Math.floor((Math.random()*1000)+1);
}

//Clear button of the new Report formn
$("#newReport_clear").click(function(){
    $("#newReport_name").val('');
    $("#newReport_phone").val('');
    $("#newReport_email").val('');
    $("#newReport_department").val('blank');
    $("#newReport_requestCategory").val('blank');
    $("#newReport_otherRequest").val('');
    $("#newReport_summary").val('');
    $("#newReport_details").val('');
    $('input[name="priority"]').prop('checked', false);
    $('#newReport_date').val('');
    $('#newReport_time').val('');
    $("#newReport_otherRequest").prop('disabled',true);
});

// Functions to execute upon page load
$(document).ready(function(){
    //New Report Modal View for Report button
    $("#report").attr("data-toggle","modal");
    $("#report").attr("data-target","#file-new-report");
    //Other category field is only enabled when request category dropdown is selected as 'other'
    $("#newReport_requestCategory").on("click",function(){
        if($(this).val()=="other_request"){
            $("#newReport_otherRequest").prop('disabled',false);
        }else{
            $("#newReport_otherRequest").prop('disabled',true);
        }
    });
    //Form Validation
    newReport_validation();
    //Datedropper
    $("#newReport_date").dateDropper();
    //Build database
    databaseBuilder();
    //TODO: Build table row & report listing
    rowBuilder();
});