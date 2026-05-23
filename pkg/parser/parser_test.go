package parser

import (
	"strings"
	"testing"
)

func TestObviouslyInvalidString(t *testing.T) {
	log := "asdf"
	_, err := Parse(log)
	if err == nil {
		t.Error("This is an invalid string that didn't error out")
	}
}

func TestOneLine(t *testing.T) {
	log := "Version: 3.4.2\nFile format: 4\nTRACE START [2025-08-19 21:12:27.842554]\n1	0	0	0.000520	360624	{main}	1		/var/www/html/happy.php	0	0\nTRACE END   [2025-08-19 21:12:27.843152]"
	logs, err := Parse(log)
	if logs == nil {
		t.Error("There should be a log file in the response")
	}
	if len(logs) != 1 {
		t.Error("There is only one log in this log file")
	}
	if err != nil {
		t.Error("There should be 0 errors in this log file")
	}

}

func TestLongTrace(t *testing.T) {
	logArray := []string{
		"Version: 3.4.2",
		"File format: 4",
		"TRACE START [2026-02-03 04:01:07.094738]",
		"1	0	0	0.000277	362256	{main}	1		/var/www/html/happy.php	0	0",
		"2	1	0	0.000297	362288	ini_set	0		/var/www/html/happy.php	5	2	'display_errors'	'1'",
		"2	1	1	0.000317	362352",
		"2	1	R			''",
		"2	2	0	0.000333	362288	exec	0		/var/www/html/happy.php	11	1	'id'",
		"2	2	1	0.040370	362400",
		"2	2	R			'uid=33(www-data) gid=33(www-data) groups=33(www-data)'",
		"2	3	0	0.040433	362288	help	1		/var/www/html/happy.php	13	1	14",
		"2	3	1	0.040450	362288",
		"2	3	R			29",
		"1	0	1	0.040466	362288",
		"			0.043026	345416",
		"TRACE END   [2026-02-03 04:01:07.137529]",
	}
	logString := strings.Join(logArray, "\n")
	logs, err := Parse(logString)
	if logs == nil {
		t.Error("There should be logs from this response")
	}
	if err != nil {
		t.Error("This is an error free log file")
	}

	if logs[0].Kind != Entry {
		t.Error("First line should be an entry line")
	}
	if logs[0].Entry.level != 1 && logs[0].Entry.funcName != "{main}" {
		t.Error("First entry wrong level or function name")
	}
	if logs[1].Kind != Entry &&
		logs[1].Exit.level != 1 {
		t.Error("1st line is not an entry line")
	}
}
