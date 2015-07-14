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
//------Page Load----------
var welcome_msg = "Hi! How can I help you today?";
$(document).ready(function(){
    $("#help-text").text(welcome_msg);
    $("#landing-buttons-wrapper").show();
    $("#newReport-ticketNumber-wrapper").hide();
    $("#login-wrapper").hide();
    $(".back").hide();
    $(".login").hide();
});

$("#newReport_trigger").on('click',function(){

});

$("#login_trigger").on('click',function() {
    $("#help-text").text("Contact the administrator if you forgot your password.");
    $("#landing-buttons-wrapper").hide();
    $("#newReport-ticketNumber-wrapper").hide();
    $("#login-wrapper").show();
    $(".back").css('margin-left','44%');
    $(".back").attr('id','fromLogin');
    $(".back").show();
    $(".login").show();
    $("#fromLogin").on('click',function() {
        $("#user_name").val('');
        $("#pass_word").val('');
        $("#landing-buttons-wrapper").show();
        $("#newReport-ticketNumber-wrapper").hide();
        $("#login-wrapper").hide();
        $(".back").hide();
        $(".login").hide();
        $("#help-text").text(welcome_msg);
    });
});

$(".login").on('click',function(){
    var usr = $("#user_name").val();
    var pass = $("#pass_word").val();
    ajaxRequest("login.php?user_name="+usr+"&pass_word="+pass, "text", null, function(returnedData){
        //console.log(returnedData);
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
            window.location.assign("http://localhost/HCSProjects/Pigeon/Main/index.html.php");
        }
    });
});
