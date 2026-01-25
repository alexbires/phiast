package parser

import (
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
