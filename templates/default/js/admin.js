/*
	Guestbook Light
	by Scripthosting.net
	
	Licensed under the "GPL Version 3, 29 June 2007"
	http://www.gnu.org/licenses/gpl.html
	
	Support-Forum: http://board.scripthosting.net
	Don't send emails asking for support!!
*/

/**
 * Ändert die Hintergrundfarbe eines Menüelements
 * @param navId id des Elements
 * @return void
 **/
function highlightNav(navId)
{	
	document.getElementById(navId).style.backgroundColor = '#E6E6E6';
	document.getElementById(navId).style.color = '#FFFFFF';
}

function setStatus(id)
{
	var ok = window.confirm('{%@Status des Eintrags wirklich ändern?}');
	if (ok) {
		document.location.href = '?action=overview&noheader=true&status=true&id='+id;
	}
}

function dropEntry(id)
{
	var ok = window.confirm('{%@Soll der Eintrag wirklich unwiderruflich gelöscht werden?}');
	if (ok) {
		document.location.href = '?action=overview&noheader=true&drop=true&id='+id;
	}
}

function statusUser(id)
{
	if (parseInt(id) != 1) {
		var ok = window.confirm("{%@Soll der Benutzerstatus wirklich geändert werden?}");
		
		if (ok) {
			document.location.href='?action=usercontrol&noheader=true&status=true&id='+id;
			return true;
		}
		else return false;
	} else {
		window.alert("{%@Der Hauptbenutzer darf nicht deaktiviert werden!}");
		return false;
	}
}

function deleteUser(id)
{
	if (parseInt(id) != 1) {
		var ok = window.confirm("{%@Soll der Benutzer wirklich unwiderruflich gelöscht werden?}");
		
		if (ok) {
			document.location.href='?action=usercontrol&noheader=true&drop=true&id='+id;
			return true;
		}
		else return false;
	} else {
		window.alert("{%@Der Hauptbenutzer darf nicht gelöscht werden!}");
		return false;
	}
}