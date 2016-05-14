class mailModifier{
    constructor(data){
        this.user = "";
        this.ticket = -1;
        this.templateType = -1;
    }
    setData(data){
        this.user = data.user;
        this.ticket = data.ticket;
        this.templateType = data.type;
    }
    getData() {
        return {user:this.user,ticket:this.ticket,type:this.templateType};
    }
    //-----------Email---------------------
    sendMail(data){
        ajaxRequest("testMail.php","text",data,function(returnedData){
            if(returnedData=="success"){
                console.log("auto mail ok!");
            }else{
                console.log(returnedData);
            }
        });
    }
    //---------Template Stuff------------------
    templateData(type){
        switch(type){
            case 0:
                return {title:"Ticket Confirmation",ticket:this.ticket};
                break;
            default:
                return "error";
        }
    }
    modifyTemplate(type){
        if(this.user!=""){
            /*var info = this.templateData(type);
            $("#title").text(info.title);
            $("#ticket").text(this.ticket);*/
            /*var info = this.templateData(type);
            ajaxRequest("/apps/pigeon/Main/mailTemplates/ticketauto.html.php","html",info,function(returnedData){
                var emailToSend = returnedData;
                console.log(emailToSend);
            });*/
        }
    }
    prepareSend(data){
        this.setData(data);
        //this.modifyTemplate(this.templateType);
        this.sendMail({reqType:this.templateType,ticket:this.ticket});
    }
}
