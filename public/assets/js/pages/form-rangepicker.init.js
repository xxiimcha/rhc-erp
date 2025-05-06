"use strict";
$(function () {
    var t = "rtl" === $("html").attr("dir") ? "left" : "right";

    $("#daterangepicker-1").daterangepicker({
        opens: t,
        cancelButtonClasses: "btn-label-danger"
    }),
        $("#daterangepicker-2").daterangepicker({
            opens: t,
            timePicker: !0,
            cancelButtonClasses: "btn-label-danger"
        }),
        $("#daterangepicker-3").daterangepicker({
            opens: t,
            singleDatePicker: !0,
            showDropdowns: !0,
            timePicker: !0,
            cancelButtonClasses: "btn-label-danger"
        }),
        $("#daterangepicker-4").daterangepicker({
            opens: t,
            startDate: moment().subtract(29, "days"),
            endDate: moment(),
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")], "Last 7 Days": [moment().subtract(6, "days"), moment()], "Last 30 Days": [moment().subtract(29, "days"), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        }),
        $("#daterangepicker-5").daterangepicker({
            opens: t,
            minDate: "04/09/2020",
            maxDate: "05/15/2020",
            cancelButtonClasses: "btn-label-danger"
        }),
        $("#daterangepicker-6").daterangepicker({
            opens: t,
            showWeekNumbers: !0,
            timePicker: !0,
            cancelButtonClasses: "btn-label-danger",
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")], "Last 7 Days": [moment().subtract(6, "days"), moment()], "Last 30 Days": [moment().subtract(29, "days"), moment()], "This Month": [moment().startOf("month"), moment().endOf("month")], "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        }),
        $("#daterangepicker-7").daterangepicker({
            opens: t,
            cancelButtonClasses: "btn-label-danger",
            locale: {
                opens: t,
                format: "MM/DD/YYYY",
                separator: " - ",
                applyLabel: "подать заявление",
                cancelLabel: "Отмена",
                fromLabel: "от",
                toLabel: "в",
                weekLabel: "н",
                daysOfWeek: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                monthNames: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
                firstDay: 1
            }
        })
})