; *** Start added by AutoIt3Wrapper ***
#include <TrayConstants.au3>
; *** End added by AutoIt3Wrapper ***
#NoTrayIcon
#Region ;**** Directives created by AutoIt3Wrapper_GUI ****
#AutoIt3Wrapper_Icon=c:\windows\system32\shell32_14.ico
#AutoIt3Wrapper_Outfile=id_remote_client-32bit.exe
#AutoIt3Wrapper_Outfile_x64=id_remote_client-64bit.exe
#AutoIt3Wrapper_Compile_Both=y
#AutoIt3Wrapper_UseX64=y
#AutoIt3Wrapper_Res_requestedExecutionLevel=asInvoker
#AutoIt3Wrapper_Add_Constants=n
#EndRegion ;**** Directives created by AutoIt3Wrapper_GUI ****

Opt("SendKeyDelay", 125)
Opt("TrayMenuMode", 3)

#include <GUIConstantsEx.au3>
#include <String.au3>
#include <Array.au3>
#include <Constants.au3>
#include <Misc.au3>
#include <ButtonConstants.au3>
#include <StaticConstants.au3>
#include <WindowsConstants.au3>
#include<ie.au3>

If _Singleton("server", 1) = 0 Then
    MsgBox(0, "Warning", "An occurence of this program is already running - Exiting now!")
    Exit
EndIf

#Region --- CodeWizard generated code Start ---
SplashTextOn("Willard Library Patron ID Generator","Remote client is loading...","-1","30","-1","-1",48,"Arial Rounded MT Bold","14","400")
#EndRegion --- CodeWizard generated code End ---

; set up the tray icon menu
;Local $settingsitem = TrayCreateMenu("Settings")
;TrayCreateItem("Ip to accecpt Connections From", $settingsitem)
;TrayCreateItem("Port Number", $settingsitem)
;TrayCreateItem("")
Local $aboutitem = TrayCreateItem("About")
TrayCreateItem("")
Global $exititem = TrayCreateItem("Exit")
TraySetIcon("Shell32.dll", 14)
TraySetState()

$active = "yes"
sleep(1500);
SplashOff()

While $active = "yes"
	accept_connections()
WEnd

Func accept_connections()
    Local $szIPADDRESS = @IPAddress4
    Local $nPORT = 33891
    Local $MainSocket, $edit, $ConnectedSocket, $szIP_Accepted
    Local $msg, $recv

    ; Start The TCP Services
    ;==============================================
    TCPStartup()

    ; Create a Listening "SOCKET".
    ;   Using your IP Address and Port 33891.
    ;==============================================
    $MainSocket = TCPListen($szIPADDRESS, $nPORT)

    ; If the Socket creation fails, exit.
    If $MainSocket = -1 Then Exit

    ; Initialize a variable to represent a connection
    ;==============================================
    $ConnectedSocket = -1

	;Wait for and Accept a connection
    ;==============================================
    Do
        $ConnectedSocket = TCPAccept($MainSocket)
		Local $msg = TrayGetMsg()
		if 	$msg  = $exititem then done();
		if 	$msg  = $aboutitem then MsgBox(0,"Willard ID Generator","ID Generator Remote client - written by Mark Ehle");
	Until $ConnectedSocket <> -1

    While 1
        $recv = TCPRecv($ConnectedSocket, 2048)
        If @error Then ExitLoop
		$recv = BinaryToString($recv, 4)
		$message = _StringExplode($recv,'@@')
		$call_workflows = $message[0]
		$paste_id  		= $message[2]
		$current_id     = $message[4]
		$patron_lname   = $message[6]
		$patron_fname   = $message[8]
		$patron_mname   = $message[10]

		if $call_workflows = "Y" then
			$iMsgBoxAnswer = MsgBox(266276,"Start User Registration","Start Workflows User Registration with current ID #? " & @CRLF & "(Make sure there are no open wizzards first! )")
			Select
				Case $iMsgBoxAnswer = 6 ;Yes
					ClipPut($current_id)
					$WorkFlows_Active = WinActivate("SirsiDynix Symphony WorkFlows")
					if $WorkFlows_Active = 0 Then
						#Region --- CodeWizard generated code Start ---
						;MsgBox features: Title=Yes, Text=Yes, Buttons=OK, Icon=Critical, Modality=System Modal
						MsgBox(4112,"Willard ID Generator Error","OOPS! SirsiDynix Workflows does not seem to be running, so I can't start the registration process! " & @CRLF & @CRLF & "I left the new ID number on the copy buffer so you can paste it in when you need it - " & $current_id)
						#EndRegion --- CodeWizard generated code End 	---
					Else
						sleep(1000)
						Send("{F11}")
						sleep(1000)
						send($current_id)
						sleep(100)
						send("{TAB}")
						send("{TAB}")
						send("{ENTER}")
						send($patron_lname)
						send("+{TAB}")
						send($patron_mname)
						send("+{TAB}")
						send("+{TAB}")
						send("+{TAB}")
						send($patron_fname)
						MsgBox(0,"Sirsi client", "data entry is complete")
					EndIf
			EndSelect
		EndIf

		if $paste_id = "Y" then
			ClipPut($current_id)
						MsgBox(64,"Willard Library Patron ID Generator","The currently printing ID card number," & $current_id & " is now on your clipboard. Press control-V to paste it into workflows.",20)


		EndIf
    WEnd
    If $ConnectedSocket <> -1 Then TCPCloseSocket($ConnectedSocket)
    TCPShutdown()
EndFunc   ;==>Example

Func done()
	TrayItemSetState($exititem , $TRAY_UNCHECKED)
	Exit
EndFunc



