class mailModifier{
    constructor(data){
        this.user = data.user;
        this.ticket = data.ticket;
        this.templateType = data.type;
        this.data = data;
    }
    getData() {
        return this.data;
    }
    templateData(type){
        switch(type){
            case 0:
                return {title:"Ticket Confirmation"};
                break;
            default:
                return "error";
        }
    }
    modifyTemplate(type){
        if(this.data!=null){
            var info = this.templateData(type);
            $("#title").text(this.data.title);
            $("#ticket").text(this.ticket);
            console.log(this.ticket);

        }
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
    prepareSend(data){
        this.putData(data);
        this.modifyTemplate(data.type);
        this.sendMail({ticket:this.ticket});
    }
}
