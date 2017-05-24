var parting = {

    days: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
    headers: [
        {
            "title": "Day",
            "style_class": "day"
        },
        {
            "title": ["0",".",".",".","4",".",".",".","8",".",".",".",],
            "style_class": "hours"
        },
        {
            "title": ["12",".",".",".","16",".",".",".","20",".",".",".",],
            "style_class": "hours"
        },
        {
            "title":"Time period(s)",
            "style_class": "time_period"
        }
    ],
    tableId: null,
    allSelected: [],
    hoursSelected: [],
    clock: null,
    hour_boxes_per_collection: 12,
    tz: "",
    domElements: [],
    //Selectors List
    tz_selector: "#tz",
    init: function(tableId, hoursSelected, tz) {

        this.tableId = "#" + tableId;

        this.addTimeZoneSupport();

        if(tz === null)
        {
            //this.tz = jstz.determine().name();
        }
        else
        {
            this.tz = tz;
        }

        this.enableTwentyFourHoursClock();
        this.setTimeZone(this.tz);

        this.drawTable();

        if(hoursSelected != null)
        {
            var select_all = [];

            $.each( hoursSelected, function( day, hours ){

                day = +day - 1;

                if(parting.hoursSelected[day] === null ||
                    typeof parting.hoursSelected[day] === 'undefined')
                {
                    parting.hoursSelected[day] = {};
                    parting.hoursSelected[day].hours = [];
                }

                $.each(hours, function(index, hour){
                    parting.hoursSelected[day].hours.push( +hour );

                    $(parting.tableId + " .day_container:eq(" + day + ") .hour_box:eq(" + hour + ")").addClass("day_parting_selected");

                    $("#day_parting_selected")
                        .append("<input type='hidden' name='day_parting[" + day + "][" + hour + "]' value='" + hour + "' />");

                    if(select_all[hour] === null ||
                        typeof select_all[hour] === 'undefined')
                    {
                        select_all[hour] = [];
                    }

                    select_all[hour].push(day);
                });
            });

            if(select_all.length > 0) {

                $.each(select_all, function (hour, day_arr) {

                    if(typeof day_arr !== 'undefined')
                    {

                        if (day_arr.length == 7) {
                            $(parting.tableId + " .all_day_container .hour_box:eq(" + hour + ")").addClass("day_parting_selected");
                            parting.allSelected[hour] = true;
                        }
                    }
                });
            }

            this.refreshTableColumns();
        }

        this.domElements = $(this.tableId + " .day_container .hour_box");

        this.createMouseEvents();

        return this;
    },
    refreshContent: function(tableId, hoursSelected, tz) {
        this.tableId = "#" + tableId; 

        if(hoursSelected != null)
        {
            var select_all = [];

            $.each( hoursSelected, function( day, hours ){

                day = +day - 1;

                if(parting.hoursSelected[day] === null ||
                    typeof parting.hoursSelected[day] === 'undefined')
                {
                    parting.hoursSelected[day] = {};
                    parting.hoursSelected[day].hours = [];
                }

                $.each(hours, function(index, hour){
                    parting.hoursSelected[day].hours.push( +hour );

                    $(parting.tableId + " .day_container:eq(" + day + ") .hour_box:eq(" + hour + ")").addClass("day_parting_selected");

                    $("#day_parting_selected")
                        .append("<input type='hidden' name='day_parting[" + day + "][" + hour + "]' value='" + hour + "' />");

                    if(select_all[hour] === null ||
                        typeof select_all[hour] === 'undefined')
                    {
                        select_all[hour] = [];
                    }

                    select_all[hour].push(day);
                });
            });

            if(select_all.length > 0) {

                $.each(select_all, function (hour, day_arr) {

                    if(typeof day_arr !== 'undefined')
                    {

                        if (day_arr.length == 7) {
                            $(parting.tableId + " .all_day_container .hour_box:eq(" + hour + ")").addClass("day_parting_selected");
                            parting.allSelected[hour] = true;
                        }
                    }
                });
            }

            this.refreshTableColumns();
        } else {
            $("#day_parting_selected").empty();

            parting.allSelected = [];
            parting.hoursSelected = [];

            $(parting.tableId + " .all_day_container .hour_box").removeClass("day_parting_selected");
            $(parting.tableId + " .day_container .hour_box").removeClass("day_parting_selected");

            this.refreshTableColumns();
        }

        parting.domElements = $(this.tableId + " .day_container .hour_box");

        //parting.createMouseEvents();

        return parting;
    },
    getSelectedDays: function() {

        var selectedHours = {};
        $.each(parting.hoursSelected, function (day, hour) {
            if(hour !== null && typeof hour !== 'undefined') {
                if(hour.hours.length > 0) {
                    selectedHours[day+1] = hour.hours;
                }
            }
        });


        return selectedHours;
    },
    createMouseEvents: function() {
        var handler = (function () {
            parting.innerEventOnBoxClick(this, "x");
        });

        $(document).mousedown(function(e) {
            if (e.which == 1 && $(e.target).hasClass("hour_box")) {
                e.preventDefault();

                $(".hour_box").bind("mouseover", handler);
            }
        }).mouseup(function(e) {
            if (e.which == 1) {
                $(".hour_box").unbind("mouseover", handler);
            }
        });

        $(".hour_box").mousedown(function(e) {
            if (e.which == 1) {
                parting.innerEventOnBoxClick(this, "x");
            }
        });
    },
    drawTable: function() {

        this.addTableHeaders()
            .addTableColumns();
    },
    refreshTable: function() {

        this.refreshTableHeaders()
            .refreshTableColumns();
    },
    addTableColumns: function() {
        for(var i = 0; i < this.days.length; i++)
        {
            //Day
            var row = "<td> " + this.days[i] + " </td>";

            for(var j = 1; j <= 24; j++)
            {
                if(j == 1)
                {
                    row = row + "<td><div class='hour_box_collection'>";
                }

                row = row + "<div data-toggle='popover' data-placement='bottom'  title='' data-content='" + this.showHoursBasedOnClock(j - 1) + "' class='hour_box' title='" + this.showHoursBasedOnClock(j - 1) + "'></div>";

                if((j % this.hour_boxes_per_collection) == 0)
                {
                    row = row + "</div></td>";

                    if(j != 24)
                    {
                        row = row + "<td><div class='hour_box_collection'>";
                    }
                }

            }

            if(i == 0)
            {
                var strtmp = "</td>";
                $(this.tableId + " tbody").append("<tr class='all_day_container'><td>Select All</td>" + row.substring(row.indexOf(row, strtmp) + strtmp.length, row.length) + "<td></td></tr>");
            }

            if(this.hoursSelected.length == 0)
            {
                row = row + "<td>No period</td>";
            }

            $(this.tableId + " tbody").append("<tr class='day_container'>" + row + "</tr>");
        }

        $('.hour_box').each(function() {
            $(this).tooltip({hide: false, show: false });
        });
    },
    addTableHeaders: function() {

        $(this.tableId + " thead").append("<tr></tr>");

        for(var i = 0; i < this.headers.length; i++)
        {
            if (typeof this.headers[i].title == "string") {
                $(this.tableId + " thead tr").append("<th class=\"" + this.headers[i].style_class + "\"> " + this.formatHeader(this.headers[i].title) + "</th>");
            } else {
                var headerFormattedContent = "<div class='title_hour_box_collection'>";

                for (var j = 0; j < this.headers[i].title.length; j++) {
                    headerFormattedContent += "<div class='title_hour_box'>" + this.headers[i].title[j] + "</div>";
                }

                headerFormattedContent += "</div>";

                $(this.tableId + " thead tr").append("<th class=\"" + this.headers[i].style_class + "\"> " + headerFormattedContent + "</th>");
            }
        }

        $(this.tableId + " thead").prepend("<tr><td class='td_title' colspan='" + parting.headers.length + "'>Ad Schedule</td></tr>");

        return this;
    },
    refreshTableHeaders: function() {

        for(var i = 0; i < this.headers.length; i++)
        {
            if (typeof this.headers[i].title == "string") {
                $(this.tableId + " thead tr th:eq(" + i + ")").html( this.formatHeader( this.headers[i].title ) );
            } else {
                var headerFormattedContent = "<div class='title_hour_box_collection'>";

                for (var j = 0; j < this.headers[i].title.length; j++) {
                    headerFormattedContent += "<div class='title_hour_box'>" + this.headers[i].title[j] + "</div>";
                }

                headerFormattedContent += "</div>";

                $(this.tableId + " thead tr th:eq(" + i + ")").html(headerFormattedContent);
            }
        }

        return this;

    },
    refreshTableColumns: function() {

        for(var i = 0; i < parting.days.length; i++)
        {
            parting.refreshTimePeriodTableColumn(i);
        }
    },
    refreshTimePeriodTableColumn: function(day) {

        if(parting.hoursSelected[day] === null ||
            typeof parting.hoursSelected[day] === 'undefined' ||
            parting.hoursSelected[day].hours.length == 0)
        {
            $(this.tableId + " .day_container:eq(" + day + ") td:last")
                .html("No period");
        }
        else
        {
            var range = [];
            var range_index = 0;
            var range_html = "";

            parting.hoursSelected[day].hours.sort(function(a,b) {
                return a - b;
            });

            for(var i = 0; i < parting.hoursSelected[day].hours.length; i++)
            {
                if(range[range_index] === null ||
                    typeof range[range_index] === 'undefined')
                {
                    range[range_index] = {};
                    range[range_index].from = parting.hoursSelected[day].hours[i];

                }

                range[range_index].to = ((parting.hoursSelected[day].hours[i] + 1) == 24) ? 0 : parting.hoursSelected[day].hours[i] + 1;

                if(parting.hoursSelected[day].hours[i+1] !== null &&
                    typeof parting.hoursSelected[day].hours[i+1] !== 'undefined' &&
                    parting.hoursSelected[day].hours[i+1] != range[range_index].to)
                {
                    range_index++;
                }
            }

            for(var i = 0; i < range.length; i++)
            {
                range_html = range_html + this.showHoursBasedOnClock(range[i].from) +
                " - " + this.showHoursBasedOnClock(range[i].to) + ", ";
            }

            range_html = range_html.substring(0, range_html.length - 2);

            $(this.tableId + " .day_container:eq(" + day + ") td:last")
                .html(range_html);
        }
    },
    formatHeader: function(value) {

        if(!isNaN(value) &&
            parseInt(Number(value)) == value &&
            !isNaN(parseInt(value, 10)))
        {
            value = this.showHoursBasedOnClock(value);
        }

        return value;
    },
    updateTooltipsText: function() {
        for(var i = 0; i < 7; i++)
        {
            for(var j = 0; j < 24; j++)
            {
                $(parting.tableId + " .day_container:eq(" + i + ") .hour_box:eq(" + j + ")")
                    .attr("title", this.showHoursBasedOnClock(j));
            }
        }
    },
    showHoursBasedOnClock: function(hour) {

        if(this.isTwelveHoursClock())
        {
            return moment({h: hour}).format("h:mm a");
        }

        return moment({h: hour}).format("H:mm");

    },
    enableTwelveHoursClock: function() {
        this.clock = 1;

        $('.clock_twentyfour').css({"font-weight" :"none", "color" : "deepskyblue"});
        $('.clock_twelve').css({"font-weight" :"bold", "color" : "#000000"});

        this.refreshTable();
        this.updateTooltipsText();
        return this;
    },
    isTwelveHoursClock: function() {
        return (this.clock == 1);
    },
    enableTwentyFourHoursClock: function() {
        this.clock = 0;

        $('.clock_twelve').css({"font-weight" :"none", "color" : "deepskyblue"});
        $('.clock_twentyfour').css({"font-weight" :"bold", "color" : "#000000"});

        this.refreshTable();
        this.updateTooltipsText();
        return this;
    },
    eventOnBoxClick: function () {
        parting.innerEventOnBoxClick(this, "x");
    },
    innerEventOnBoxClick: function (el, direction) {

        var isAllElement = $(el).closest("tr").hasClass("all_day_container");
        var index, day, hour, array_key;

        day = $('.day_container').index($(el).closest('.day_container'));

        if (!isAllElement) {
            index = $("table" + parting.tableId + " tr:not(.all_day_container) .hour_box").index(el);
            hour = index - (day * 24);
        } else {
            index = $("table" + parting.tableId + " tr.all_day_container .hour_box").index(el);
            hour = index;
        }

        if (!isAllElement) {
            if (typeof parting.hoursSelected[day] == 'undefined' || parting.hoursSelected[day] === null) {
                parting.hoursSelected[day] = {};
                parting.hoursSelected[day].hours = [];
            }

            array_key = $.inArray(hour, parting.hoursSelected[day].hours);
        }

        if (direction == "x") {
            if (!isAllElement) {
                if (array_key == -1) {
                    parting.hoursSelected[day].hours.push(hour);
                    $("#day_parting_selected").append("<input type='hidden' name='day_parting[" + day + "][" + hour + "]' value='" + hour + "' />");
                    $(el).addClass("day_parting_selected");

                    var allSelected = true;
                    for (var i = 0; i < parting.days.length; i++) {
                        if (typeof parting.hoursSelected[i] == 'undefined' || parting.hoursSelected[i] === null) {
                            allSelected = false;
                        } else {
                            if ($.inArray(hour, parting.hoursSelected[i].hours) == -1) {
                                allSelected = false;
                            }
                        }
                    }

                    if (allSelected) {
                        parting.allSelected[hour] = true;
                        $("table" + parting.tableId + " tr.all_day_container .hour_box:eq(" + hour + ")").addClass("day_parting_selected");
                    }
                } else {
                    parting.hoursSelected[day].hours.splice(array_key, 1);
                    $('#day_parting_selected input[name="day_parting[' + day + '][' + hour + ']"]').remove();
                    $(el).removeClass("day_parting_selected");

                    if (typeof parting.allSelected[hour] != "undefined" && parting.allSelected[hour]) {
                        parting.allSelected[hour] = false;
                        $("table" + parting.tableId + " tr.all_day_container .hour_box:eq(" + hour + ")").removeClass("day_parting_selected");
                    }
                }

                parting.hoursSelected[day].hours.sort(function (a, b) {
                    return a - b;
                });

                parting.refreshTimePeriodTableColumn(day);
            } else {
                if (typeof parting.allSelected[hour] == "undefined") {
                    parting.allSelected[hour] = false;
                }

                var direction;
                if (!parting.allSelected[hour]) {
                    parting.allSelected[hour] = true;
                    $(el).addClass("day_parting_selected");
                    direction = "1";
                } else {
                    parting.allSelected[hour] = false;
                    $(el).removeClass("day_parting_selected");
                    direction = "0";
                }

                //This is starting in 0 because we get the elements without the all_day_container ones, so indexes starts at zero
                for (var i = 0; i < parting.days.length; i++) {
                    var clickElement = parting.domElements[(hour + (i * 24))];

                    parting.innerEventOnBoxClick(clickElement, direction);

                    parting.hoursSelected[i].hours.sort(function (a, b) {
                        return a - b;
                    });

                    parting.refreshTimePeriodTableColumn(i);
                }
            }
        } else {
            if (!isAllElement) {
                if (direction == "1") {
                    if (array_key == -1) {
                        parting.hoursSelected[day].hours.push(hour);
                        $("#day_parting_selected").append("<input type='hidden' name='day_parting[" + day + "][" + hour + "]' value='" + hour + "' />");
                    }

                    $(el).addClass("day_parting_selected");
                } else {

                    if (array_key > -1) {
                        parting.hoursSelected[day].hours.splice(array_key, 1);
                        $('#day_parting_selected input[name="day_parting[' + day + '][' + hour + ']"]').remove();
                    }

                    $(el).removeClass("day_parting_selected");
                }
            }
        }
    },
    setTimeZone: function(tz) {

        if(tz !== null)
        {
            parting.tz = tz;
            $("#tz").html(parting.tz);
            $(".timezone_section input[name=parting_timezone]").val(parting.tz);
        }

    },
    addTimeZoneSupport: function() {

        $( "#tz_country_modal" ).dialog({
            autoOpen: false,
            resizable: false,
            height: 105,
            width: 278,
            position: {
                my: "top",
                at: "left bottom",
                of: "#tz"
            }
        });

        $( this.tz_selector ).click(function()
        {
            $( "#tz_country_modal" ).dialog("open");
        });

        this.getTZByCountry();
    },
    getTZByCountry: function() {

        return; 

        $.getJSON( "ext/countries.json", function( data ) {

            var selector = $("#country_list");

            selector.append("<option value=''></option>");

            $.each( data, function( key, val ) {
                selector.append("<option value='" + key + "'> " + val + " </option>");
            });

            selector.on('change', function(evt, params) {

                dataObj = {"country_code": params.selected};

                $.ajax({
                    type: 'GET',
                    url: "ajax/timezone.php",
                    data: dataObj,
                    success: function(json_results) {

                        json_results = $.parseJSON( json_results );

                        if(json_results.timezones !== null)
                        {
                            if(json_results.timezones.length == 1)
                            {
                                parting.setTimeZone(json_results.timezones[0]);

                                $( "#tz" ).show();
                                $( "#timezone_list" ).remove();
                                $( "#timezone_list_chosen").remove();

                                $( "#tz_country_modal" ).dialog("close");
                            }
                            else
                            {
                                var selector_tz = $("#timezone_list");

                                if( selector_tz.length == 0 ) {

                                    $("#tz_country_modal")
                                        .append('<select data-placeholder="Choose a timezone..." class="chosen-select" id="timezone_list"></select>');

                                    selector_tz = $("#timezone_list");
                                    selector_tz.append("<option value=''></option>");

                                    $.each( json_results.timezones, function( key, val ) {
                                        selector_tz.append("<option value='" + val + "'> " + val + " </option>");
                                    });

                                    selector_tz.chosen({ width: '250px' });

                                    selector_tz.on('change', function(evt, params) {

                                        parting.setTimeZone(params.selected);

                                        $( "#tz_country_modal" ).dialog("close");


                                    });
                                }
                                else
                                {
                                    selector_tz.empty();

                                    selector_tz.append("<option value=''></option>");

                                    $.each( json_results.timezones, function( key, val ) {
                                        selector_tz.append("<option value='" + val + "'> " + val + " </option>");
                                    });

                                    selector_tz.trigger('chosen:updated')
                                }



                            }
                        }
                    }
                });
            });

            selector.chosen({ width: '250px' });
        });
    }
};