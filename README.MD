# FFNRW-Knotenregistrierung

Zwischenlösung zur Registrierung neu eingerichteter Freifunk-Knoten auf Basis der Infrastruktur des "Verbund freier Netzwerke NRW e.V."

## Notwendige Schritte zur Installation

1. In der Datei "constants.php" muss die URL der Installation angegeben werden.
2. In der Datei "configuration.php" muss die Verbindungsdaten zur MySQL Datenbank angegeben werden.
3. In der Datei "database-abstraction/requests.php" müssen in den Funktionen "sendValidationMail" und "sendApprovalMail" die Verbindungsdaten zum benutzten SMTP Server angegeben werden. Sowie - im Fall von "sendValidationMail" die Mailadresse des Administrators.

## Verwendete Fremdsoftware

* Bootstrap (http://getbootstrap.com)
* JQuery (http://jquery.com)
* SLIM Framework (http://www.slimframework.com)
* Smarty (http://www.smarty.net)
* PHPMailer (https://github.com/Synchro/PHPMailer)
* JQuery Cookie (https://github.com/carhartl/jquery-cookie)