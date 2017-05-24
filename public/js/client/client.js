var j = jQuery.noConflict();//try to find out solution sharmila instead of using this!!!!!! ok I will do it later
j(document).ready(function(){
	//var url="http://localhost/adminproject/client";
	var url="/client";
	

	// for changing client and id 	
	var jselect = j('#client,#id');
	jselect.change(function() {
		jselect.val(this.value);
		jselect.selectpicker('refresh');
	}).change();
	
	// for clear filter
    j('#clear').click(function(){
        //j('.clean-select').prop('selectedIndex',0);
		  var jselect = j('.clean-select');
		 jselect.val(jselect.data('default'));
		
		j('.clean-select').selectpicker('refresh');
    });
	
	
	//Display datatables for the first time	
	j('#clientTable').DataTable().destroy();
	var dataTable=j('#clientTable').DataTable({
		"processiong":true,
		"serverSide":true,
		"searching":false,
		"order":[],
		"ajax":{
			//url:"<?php echo jurl.'/client/fetchData';?>",
			url:url+'/searchData',
			type:"POST",
			data:{type:'',status:'',geo:'',io:'',am:'',id:'',content:''},
		},
		"columnDefs":[
		{
			"targets":[10],
			"orderable":false,
		},
		],	
	});
	
		
	function searchAll(searchvalue){
		//searchall and use datatable code here
		j('#clientTable').DataTable().destroy();
		var dataTable=j('#clientTable').DataTable({
		"processiong":true,
		"serverSide":true,
		"searching":false,
		"order":[],
		"ajax":{
			url:url+"/searchAll",
			type:"POST",
			data: {searchvalue:searchvalue},
		},
		"columnDefs":[
		{
			"targets":[10],
			"orderable":false,
		},
		],	
	});			
	}
	
	
	
	//returns the maximum row that the database table contain
	function getTotalRows(){
		j.ajax({
			url: url+"/getTotalRows",
			type: "POST",
			dataType:"json",
			success:function(rows){
				max_row=rows;
				//return rows;
						                      
			},
			error:function(){
				//write some code here
			}            
		});	
	}
	
	function searchData(type,status,geo,io,am,id,content){	
		j('#clientTable').DataTable().destroy();
		var dataTable=j('#clientTable').DataTable({
		"processiong":true,
		"serverSide":true,
		"searching":false,
		"order":[],
		"ajax":{
			url:url+"/searchData",
			type:"POST",
			data: {type:type,status:status,geo:geo,io:io,am:am,id:id,content:content},
		},
		"columnDefs":[
		{
			"targets":[10],
			"orderable":false,
		},
		],	
	});				
	}
									

	function createClient(formData){
		j('.alert-success').css('display','none');
		j('.alert-danger').css('display','none');
		//alert('I m here creatClient javascript');
		console.log(formData);
		j.ajax({
			url: url+"/saveClient",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success:function(msg){
				if(msg=='success'){
				j('.alert-success').css('display','block');
				window.scrollTo(0, 0);
				}
				else{
					j('.alert-danger').css('display','block');
					window.scrollTo(0, 0);
				}
				
				//alert(msg);
                      
			},
			error:function(){
				j('.alert-danger').css('display','block');
				window.scrollTo(0, 0);
				//alert(msg);
			}     
			
		}); 
		
	}

	function updateClient(formData){
		j('.alert-success').css('display','none');
		j('.alert-danger').css('display','none');
		j.ajax({
			url: url+"/getClientUpdated",
			type: "POST",
			data: formData,
			contentType: false,
			cache: false,
			processData: false,
			success:function(msg){
				if(msg=='success'){
					j('.alert-success').css('display','block');
					window.scrollTo(0, 0);
				}
				else{
					j('.alert-danger').css('display','block');
					window.scrollTo(0, 0);
				}
				
                      
			},
			error:function(){
				//alert('Error');
				j('.alert-danger').css('display','block');
				window.scrollTo(0, 0);
				/*
				location.reload();
				 window.scrollTo(0, 0);*/
				//alert(msg);
			}            
		}); 
	}


	j( "#form1" ).submit(function( event ) {
		
		event.preventDefault();
		var formData = new FormData(this);
		console.log(formData);	
		if(j('input[type=file]').val()!='')
		{
			var ext = j('input[type=file]').val().split('.').pop().toLowerCase();
			if(j.inArray(ext, ['pdf','docx','jpg','jpeg']) == -1) {
				alert('Please choose pdf/docx/jpg/jpeg format. Choosen is invalid extension!');
			}
		}	
		createClient(formData);
		//clear the form for the next time
		var jselect = j('.clean-select1');
		jselect.val(jselect.data('default'));
		j('.clean-select1').selectpicker('refresh');
		
	});

	
	j("#form2").submit(function( event ) {
		
		event.preventDefault();
		var formData = new FormData(this);
		console.log(formData);	
		if(j('input[type=file]').val()!='')
		{
			var ext = j('input[type=file]').val().split('.').pop().toLowerCase();
			if(j.inArray(ext, ['pdf','docx','jpg','jpeg']) == -1) {
				alert('Please choose pdf/docx/jpg/jpeg format. Choosen is invalid extension!');
			}
		}	
		updateClient(formData);
		//window.location = "http://mobisteinlp.com/adminproject/client/";
	});
		
	
	j('#search').click(function(){	
		var geo=j('#geo').val();
		var type=j('#type').val();
		var content=j('#content').val();
		var status=j('#status2').val();
		var io=j('#io').val();
		var am=j('#am').val();
		var id=j('#id').val();
		searchData(type,status,geo,io,am,id,content);
			
	});
	
	j('#searchAll').keypress(function(e) {
		if(e.which == 13) {
			if(j(this).val().length>=3){
			// call ajax and create datatables
				var searchvalue=j(this).val();
				searchAll(searchvalue);
			}
			else{
				alert('Please enter at least 3 characters!');
			
			}
		}
	});
	
    j(document).on("click", ".hideRow", function(){ // This is the changed line
		var result = confirm("Are you sure you want to delete?");
		if (result) {
        var id = j(this).attr('id'); 
		//console.log('working');
		//alert(id);
		var geo=j('#geo').val();
		var type=j('#type').val();
		var content=j('#content').val();
		var status=j('#status2').val();
		var io=j('#io').val();
		var am=j('#am').val();
		var id1=j('#id').val();
		
		j.ajax({
			url: url+"/hideClient",
			type: "POST",
			data:{id:id},
			dataType:"json",
			success:function(msg){
				//call datatables
				                   
			},
			error:function(){
				//alert(msg);
			}            
		}); 
		
		searchData(type,status,geo,io,am,id1,content);   
		}
		
    });
	
	
	
	//tiana code here
	j('.dropdown-toggle').dropdown(); 
	var expanded = false;
    function showCheckboxes() {
    var checkboxes = document.getElementById("checkboxes");
    if (!expanded) {
        checkboxes.style.display = "block";
        expanded = true;
    } 
	else {
        checkboxes.style.display = "none";
        expanded = false;
    }
    }

    //Multiple select 
	//tiana code end here
	 
	//for multiple select
		
	j('.copyemail').click(function(){
		var string='';
		j("tr").each(function() {
			var text=j(this).find('td:eq(5)').text();
			var checkstring= text.toLowerCase();
			 if(text){
				if(checkstring!=='check am'){
					string+=text+",";
				}
			}
		});
		string=string.replace(/^[,\s]+|[,\s]+$/g, '');
		j(this).attr('data-clipboard-text', string);
		
		
	});
	
	// for checkbox when select all disable other select
	//awesome andré check/unckeck solution on tânia code here

    function toggleSelectAll(control) {
    var allOptionIsSelected = (control.val() || []).indexOf("All") > -1;
    function valuesOf(elements) {
        return j.map(elements, function(element) {
            return element.value;
        });
    }

    if (control.data('allOptionIsSelected') != allOptionIsSelected) {
        // User clicked 'All' option
        if (allOptionIsSelected) {
            // Can't use .selectpicker('selectAll') because multiple "change" events will be triggered
            control.selectpicker('val', valuesOf(control.find('option')));
        } else {
            control.selectpicker('val', []);
        }
    } else {
        // User clicked other option
        if (allOptionIsSelected && control.val().length != control.find('option').length) {
            // All options were selected, user deselected one option
            // => unselect 'All' option
            control.selectpicker('val', valuesOf(control.find('option:selected[value!=All]')));
            allOptionIsSelected = false;
        } else if (!allOptionIsSelected && control.val().length == control.find('option').length - 1) {
            // Not all options were selected, user selected all options except 'All' option
            // => select 'All' option too
            control.selectpicker('val', valuesOf(control.find('option')));
            allOptionIsSelected = true;
        }
    }
    control.data('allOptionIsSelected', allOptionIsSelected);
}

	j('#type,#geo,#content').selectpicker().change(function(){toggleSelectAll(j(this));}).trigger('change');
	
	
	
	/*
	var $box = j('#messages'); 
	var height = $box.get(0).scrollHeight;
	$box.scrollTop(height);
	
	var $box1 = j('#messages2'); 
	var height = $box1.get(0).scrollHeight;
	$box1.scrollTop(height);
	*/
	
	//j('#messages2').scrollTop(j('#messages2')[0].scrollHeight);
	//j('#messages').scrollTop(j('#messages')[0].scrollHeight);
	//j("#div1").animate({ scrollTop: $("#div1")[0].scrollHeight}, 1000);
	//j(".tab-content").animate({ scrollTop: j(".tab-content")[0].scrollHeight}, 1000);
});

