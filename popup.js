 

 $(document).ready(function(){
        //Скрыть PopUp при загрузке страницы    
        PopUpHide();
        PopUpHide2();
    });
    //Функция отображения PopUp
    function PopUpShow(){
        $("#popup1").show();
    }
    //Функция отображения PopUp
    function login_PopUpShow(){
        $("#login_popup").show();
    }
    function login_PopUpHide(){
        $("#login_popup").hide();
    }
        // удаление клиента
    function del_Show(num_del){
        let form_name = "#del" + num_del;
        $(form_name).show();
    }
    function del_Hide(num_del){
        let form_name = "#del" + num_del;
        $(form_name).hide();
    }


    //Функция скрытия PopUp
    function PopUpHide(){
        $("#popup1").hide();
    }
    function PopUpShow2(id){
    	let form_name = "#popup2_" + id;
        $(form_name).show();
    }
    //Функция скрытия PopUp
    function PopUpHide2(id){
    	let form_name = "#popup2_" + id;
        $(form_name).hide();
    }
        function PopUpHide3(id){
        let form_name = "#popup" + id;
        $(form_name).hide();
    }
        function Groups(){
        $("#groups_popup").show();
    }
        function Groups_hide(){
        $("#groups_popup").hide();
    }
    function Support(area){
        let form_id = "#" + area;
        $(form_id).show();
    } 
    function Support_hide(area){
        let form_id = "#" + area;
        $(form_id).hide();
    }  
    function ShowLogin(ids){
        let formid = "#id" + ids;
        $(formid).show();
    } 
    function ShowLogin_hide(ids){
        let formid = "#id" + ids;
        $(formid).hide();
    }        

$(document).click( 
    function(){
    // клик снаружи элемента 
    $("#popup3").fadeOut();
    $("#popup4").fadeOut();
});






