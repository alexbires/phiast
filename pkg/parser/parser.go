package parser

import (
	"errors"
	"strconv"
	"strings"
)

type Record int

const (
	Entry Record = 0
	Return
	Exit
)

type XDebugLine struct {
	Kind Record

	Entry  *EntryLine
	Exit   *ExitLine
	Return *ReturnRecord
}

type EntryLine struct {
	level      uint32   //the level of execution
	funcNo     uint32   // the number of the function we are calling
	timeIndex  float64  //the time index of where we are executing
	memUsage   uint64   // how much memory we are using
	funcName   string   // name of the function
	uoi        uint8    // user defined (1) or internal (0) function
	fileName   string   // name of the file we are pulling from
	lineNumber uint32   // the line number
	argCount   uint32   // the number of argumetns the function takes in
	arguments  []string //the arguments we have passed into the function
}

type ExitLine struct {
	level     uint32  //the level of execution
	funcNo    uint32  // the number of the function we are calling
	timeIndex float64 //the time index of where we are executing
	memUsage  uint64  // how much memory we are using
}

type ReturnRecord struct {
	level    uint32 //the level of execution
	funcNo   uint32 // the number of the function we are calling
	retValue string // the return value
}

// Returns an array of log lines for the given trace
func Parse(logs string) ([]XDebugLine, error) {
	log_lines := strings.Split(logs, "\n")

	if len(log_lines) < 4 {
		return nil, errors.New("Not enough lines to process")
	}
	parsedLines := make([]XDebugLine, len(log_lines)-4)

	for i := 3; i < len(log_lines)-1; i++ {
		entries := strings.Split(log_lines[i], "\t")
		switch entries[2] {
		case "0":
			e := handleEntryLine(entries)
			parsedLines[i-3] = e
		case "1":
			e := handleExitLine(entries)
			parsedLines[i-3] = e

		case "R":
			lvl, _ := strconv.ParseUint(entries[0], 10, 32)
			fnc, _ := strconv.ParseUint(entries[1], 10, 32)
			return_line := XDebugLine{
				Kind: Return,
				Return: &ReturnRecord{
					level:    uint32(lvl),
					funcNo:   uint32(fnc),
					retValue: entries[5],
				},
			}
			parsedLines[i-3] = return_line
		}
	}
	//fmt.Println(log_lines[0])
	return parsedLines, nil
}

// handleExitLine is a helper function that will handle the parsing
// of an exit line into the appropriate struct and return it.
func handleExitLine(entries []string) XDebugLine {
	lvl, _ := strconv.ParseUint(entries[0], 10, 32)
	fnc, _ := strconv.ParseUint(entries[1], 10, 32)
	tme, _ := strconv.ParseFloat(entries[2], 64)
	mem, _ := strconv.ParseUint(entries[4], 10, 64)
	e := XDebugLine{
		Kind: Exit,
		Exit: &ExitLine{
			level:     uint32(lvl),
			funcNo:    uint32(fnc),
			timeIndex: tme,
			memUsage:  mem,
		},
	}
	return e
}

func handleEntryLine(entries []string) XDebugLine {
	lvl, _ := strconv.ParseUint(entries[0], 10, 32)
	fnc, _ := strconv.ParseUint(entries[1], 10, 32)
	tme, _ := strconv.ParseFloat(entries[3], 64)
	mem, _ := strconv.ParseUint(entries[4], 10, 16)
	u, _ := strconv.ParseUint(entries[6], 10, 8)
	line, _ := strconv.ParseUint(entries[9], 10, 32)
	arg, _ := strconv.ParseUint(entries[10], 10, 32)
	e := XDebugLine{
		Kind: Entry,
		Entry: &EntryLine{
			level:      uint32(lvl),
			funcNo:     uint32(fnc),
			timeIndex:  tme,
			memUsage:   mem,
			funcName:   entries[5],
			uoi:        uint8(u),
			fileName:   entries[8],
			lineNumber: uint32(line),
			argCount:   uint32(arg),
			arguments:  make([]string, arg),
		},
	}
	return e
}
