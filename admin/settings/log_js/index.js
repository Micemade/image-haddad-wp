jQuery.noConflict();
(function ($) {
	"use strict";
	$(document).ready(function () {
		// Delete DB entry.
		$(".del-entry").click(function (e) {
			e.preventDefault();

			var thisButton = $(this),
				dbid = thisButton.data("dbid"),
				row = thisButton.closest(".container");

			row.css("background", "pink");

			var data = {
				action: "delete_dblog_entry",
				nonce: logAjax.nonce,
				dbid: dbid,
			};

			$.ajax({
				type: "POST",
				url: logAjax.url,
				data,
				success: function (response) {
					row.remove();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log(
						$.parseJSON(jqXHR.responseText) +
							" :: " +
							textStatus +
							" :: " +
							errorThrown
					);
				},
			});
		});

		// Delete ONE JSON entry.
		$(".del-json").click(function (e) {
			e.preventDefault();

			var thisButton = $(this),
				tstamp = thisButton.data("tstamp"),
				row = thisButton.closest(".container");

			row.css("background", "pink");

			var data = {
				action: "delete_json_entry",
				nonce: logAjax.nonce,
				tstamp: tstamp,
			};

			$.ajax({
				type: "POST",
				url: logAjax.url,
				data,
				success: function (response) {
					row.remove();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log(
						$.parseJSON(jqXHR.responseText) +
							" :: " +
							textStatus +
							" :: " +
							errorThrown
					);
				},
			});
		});

		// Delete MULTIPLE JSON entries.
		$("#del_multiple").click(function () {
			var checkboxes = $("input[data-item=delete]");
			var tstamp_arr = [];

			checkboxes.each(function () {
				var cbox = $(this),
					tstamp = cbox.attr("name"),
					row = cbox.closest(".container");

				row.css("background", "");

				if (cbox.prop("checked") == true) {
					row.css("background", "pink").addClass("item-to-remove");
					tstamp_arr.push(tstamp);
				}
			});

			if (Array.isArray(tstamp_arr) && !tstamp_arr.length) {
				alert("Niste odabrali niti jednu stavku");
				return;
			} else if (
				!confirm("Jeste li sigurni da želite pobrisati odabrane stavke? ")
			) {
				$(".container").css("background", "").removeClass("item-to-remove");
				tstamp_arr = [];
				return;
			}

			var data = {
				action: "delete_json_entry",
				nonce: logAjax.nonce,
				tstamp: tstamp_arr.join(","),
			};

			$.ajax({
				type: "POST",
				url: logAjax.url,
				data,
				success: function (response) {
					$(".item-to-remove").remove();
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log(
						$.parseJSON(jqXHR.responseText) +
							" :: " +
							textStatus +
							" :: " +
							errorThrown
					);
				},
			}); // end ajax
		}); // end del_mutliple

		$("#select-all-checkboxes").click(function () {
			$("input:checkbox").not(this).prop("checked", this.checked);
		});
	});
})(jQuery);
