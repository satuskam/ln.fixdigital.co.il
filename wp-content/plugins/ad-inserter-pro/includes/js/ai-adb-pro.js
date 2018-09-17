// FuckAdBlock (v3.2.1)
if (typeof funAdBlock === "undefined") {
  jQuery (document).ready (function () {if (!ai_adb_active || ai_adb_debugging) ai_adb_detected (5)});
} else {
    funAdBlock.onDetected (function () {if (!ai_adb_active || ai_adb_debugging) ai_adb_detected (5)});
    funAdBlock.onNotDetected (function () {ai_adb_undetected (5)});
  }

// FuckAdBlock (4.0.0-beta.3)
if (typeof badBlock === "undefined") {
    jQuery(document).ready (function () {if (!ai_adb_active || ai_adb_debugging) ai_adb_detected (6)});
} else {
    badBlock.on (true, function () {if (!ai_adb_active || ai_adb_debugging) ai_adb_detected (6)}).on (false, function () {ai_adb_undetected (6)});
}

badBlock = undefined;
BadBlock = undefined;
