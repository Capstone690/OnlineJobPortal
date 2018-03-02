//back button click on each form page
var backBtn = document.getElementById("back");
	if(backBtn){
		backBtn.addEventListener("click",function(){
			window.history.back();
		});		
	}
function addRequiredMark(formId){
	$('#'+formId).find('select, textarea, input').each(function(){ //alert($(this).prop('required'));
          if($(this).prop('required')){
            $(this).closest(':has(label)').find('label').after("<span style='color:red'> *</span>");
          }
        });
}