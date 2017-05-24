$(document).ready(function () {
    var parser = document.createElement('a');
    parser.href = window.location.href;
    var table = $('#tableBackups').DataTable( {
	    "fnDrawCallback": function( oSettings ) {
	        bindButtonClick();
	    }
  	});

  	var table2 = $('#tableTemBlocker').DataTable( {
	    "fnDrawCallback": function( oSettings ) {
	        bindButtonClick4();
	    }
            ,"order": [[ 2, "desc" ]]
  	});

        $('#tableTempBlocker').DataTable({"order": [[ 2, "desc" ]]});

    var campaign = '';
    $("#buttonSearchCampaign").on('click', function () {

        if ($("#campaigns").val() !== '') {

            formData = new FormData();
            formData.append('campaignName', $("#campaigns").val());
            campaign = $("#campaigns").val();

            $.ajax({
                url: '/campaignblocker/getAffected',
                data: formData,
                type: 'POST',
                async: true,
                success: function (data) {
                    var json = $.parseJSON(data);

                    $('#tbodynjumps').empty();
                    $('#tbodymjumps').empty();

                    $("#tbodynjumps").html(json['njumpstable']);
                    $("#tbodymjumps").html(json['mjumpstable']);

                    $("#mjumpsID").html("MJump's (" + json['mjumpstotal'] + " rows)");
                    $("#njumpsID").html("NJump's (" + json['njumpstotal'] + " rows)");

                    if (json['mjumpstotal'] == '0' && json['njumpstotal'] == '0') {
                        $("#titleChanges").text('Njumps and Mjumps are empty for this campaign');
                    } else {
                        $("#titleChanges").text('These njumps/mjumps will be modified');
                    }

                    $(".firststep").show();
                    $(".secondstep").hide();
                },
                error: function (response) {
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });


        } else {
            alert('Campaign name is empty!');
        }

    });

    $("#buttonBlockCampaign").on('click', function () {

        if (($("input[name='backupName']").val() !== '') && ($("input[name='backupDescription']").val() !== '')) {

            formData = new FormData();
            formData.append('backupname', $("input[name='backupName']").val());
            formData.append('backupdescription', $("input[name='backupDescription']").val());
            formData.append('campaign', campaign);

            $.ajax({
                url: '/campaignblocker/executeblock',
                data: formData,
                type: 'POST',
                async: true,
                success: function (data) {
                    var json = $.parseJSON(data);

                    $('#tbodynjumps2').empty();
                    $('#tbodymjumps2').empty();

                    $("#tbodynjumps2").html(json['njumpstable']);
                    $("#tbodymjumps2").html(json['mjumpstable']);

                    if (json['njumpstable'] == '' && json['njumpstable'] == '') {
                        $("#titleChanges2").text('No need to check modified njumps/mjumps.');
                    } else {

                        $("#titleChanges2").text('Please check these njumps/mjumps. They are completely blocked ');
                    }
                    $("#mjumpsID2").html("MJump's");
                    $("#njumpsID2").html("NJump's");

                    if (typeof table != 'undefined' && table != null)
                        table.destroy();

                    $('#tbodytableBackups').empty();
                    $("#tbodytableBackups").html(json['backupstable']);
                    table = $('#tableBackups').DataTable();

                    $(".firststep").hide();
                    $(".secondstep").show();
                    bindButtonClick();
                },
                error: function (response) {
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });

        } else {
            alert('Backup name and Backup description cannot be empty!');
        }

    });

    bindButtonClick();

    function bindButtonClick() {
        $( ".modalIcon" ).unbind();
        $(".modalIcon").on('click', function () {
            bindAction(this);
        });
    }

    bindButtonClick4();

    function bindButtonClick4() {
        $( ".modalIcon2" ).unbind();
        $(".modalIcon2").on('click', function () {
            bindAction4(this);
        });
    }

    function bindAction(e) {
        if (confirm('Are you sure you want to restore this backup?')) {

            formData = new FormData();


            formData.append('backuphash', $(e).attr('id'));

            $.ajax({
                url: '/campaignblocker/executerestore',
                data: formData,
                type: 'POST',
                async: true,
                success: function (data) {

                    if (typeof table != 'undefined' && table != null)
                        table.destroy();

                    $('#tbodytableBackups').empty();
                    $("#tbodytableBackups").html(data);
                    table = $('#tableBackups').DataTable();

                    $(".modalIcon").bind("click", function () {
                        bindAction();
                    });

                },
                error: function (response) {
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });

        } else {
            console.log("ok");
        }
    }

    jQuery.fn.filterByText = function (textbox) {
        return this.each(function () {
            var select = this;
            var options = [];
            $(select).find('option').each(function () {
                options.push({value: $(this).val(), text: $(this).text()});
            });
            $(select).data('options', options);

            $(textbox).bind('change keyup', function () {
                var options = $(select).empty().data('options');
                var search = $.trim($(this).val());
                var regex = new RegExp(search, "gi");
                $.each(options, function (i) {
                    var option = options[i];
                    if (option.text.match(regex) !== null) {
                        $(select).append(
                                $('<option>').text(option.text).val(option.value)
                                );
                    }
                });
            });
        });
    };

    $('#aggNames').filterByText($('#aggfilter'));

    $("#aggNames").change(function () {
        $.ajax({
            "url": 'http://' + parser.hostname + '/campaignblocker/getcampaigns?aggID=' + $('#aggNames').val(),
            "success": function (json) {
                if (json == 0) {
                    alert('no campaigns');
                } else {
                    $('#campaigns').empty();
                    $('#campaigns').append('<option id=""></option>');
                    $.each($.parseJSON(json), function (k, v) {
                        $('#campaigns').append('<option id="' + v.name + '">' + v.name + '</option>');
                    });
                }
            }
        });

    });

    $("#blockoffersinput").on('click', function () {
    	if (confirm('Are you sure you want to block this offers?')) {

            formData = new FormData();


            formData.append('offersname', $("#inputNamesOffers").val());

            $.ajax({
                url: '/campaignblocker/blockbyname',
                data: formData,
                type: 'POST',
                async: true,
                success: function (data) {

                    if (typeof table2 != 'undefined' && table2 != null)
                        table2.destroy();

                    $('#tbodytableTempBlocker').empty();
                    $("#tbodytableTempBlocker").html(data);
                    table2 = $('#tableTemBlocker').DataTable({"order": [[ 2, "desc" ]]});

                    $(".modalIcon2").bind("click", function () {
                        bindAction4();
                    });

                },
                error: function (response) {
                    alert("error");
                },
                cache: false,
                contentType: false,
                processData: false
            });

        } else {
            console.log("ok");
        }
    });

	function bindAction4(e) {
       	if (confirm('Are you sure you want to restore this offers?')) {

	        formData = new FormData();
	        formData.append('offername', $(e).attr('offername'));
	        formData.append('backuptime', $(e).attr('backuptime'));

	        $.ajax({
	            url: '/campaignblocker/executetemprestore',
	            data: formData,
	            type: 'POST',
	            async: true,
	            success: function (data) {

	                if (typeof table2 != 'undefined' && table2 != null)
	                    table2.destroy();

	                $('#tbodytableTempBlocker').empty();
	                $("#tbodytableTempBlocker").html(data);
	                table = $('#tableTemBlocker').DataTable({"order": [[ 2, "desc" ]]});

	                $(".modalIcon2").bind("click", function () {
	                    bindAction4();
	                });

	            },
	            error: function (response) {
	                alert("error");
	            },
	            cache: false,
	            contentType: false,
	            processData: false
	        });

	    } else {
            console.log("ok");
        }
    }

});

