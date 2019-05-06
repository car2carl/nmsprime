<?php

/*
|--------------------------------------------------------------------------
| Language lines for module ProvVoipEnvia
|--------------------------------------------------------------------------
|
| The following language lines are used by the module ProvVoipEnvia
| As far as we know this module is in use in Germany, only. So no translation
| for other languages is needed at the moment.
|
*/

return [
    'activationdatenotsetfornumber' => 'Kein Aktivierungsdatum für Rufnummer :0 gesetzt',
    'addfuturevoipitem' => 'wahrscheinlich müssen Sie ein VoIP-Produkt mit einem Startdatum in der Zukunft anlegen.',
    'allnumberssameekp' => 'Alle Rufnummern müssen den gleichen eingehenden EKP-Code haben.',
    'allportedornone' => 'Entweder alle oder keine Rufnummer müssen zu portieren sein – eine Mischung ist nicht zulässig.',
    'anotsetinb' => ':0 nicht gesetzt in in :1',
    'available_keys' => 'Verfügbare Schlüsselwerte',
    'change_installation_address_sucessful' => 'Installationsadresse erfolgreich geändert (Auftrags-ID :0)',
    'change_method_sucessful' => 'Sprachprotokoll erfolgreich geändert (Auftrags-ID :0)',
    'change_tariff_sucessful' => 'Verkaufstarif erfolgreich geändert (Auftrags-ID :0)',
    'change_variation_sucessful' => 'Einkaufstarif erfolgreich geändert (Auftrags-ID :0)',
    'changing_envia_cust_id' => 'Ändere die envia-TEL-Kundenreferenz von :0 in :1.',
    'contract_create_different_customer_ids' => 'Fehler beim Verarbeiten (envia-TEL-Auftrags-ID: :0): Die im System vorhandene envia-TEL-Kunden-ID (:1) unterscheidet sich von der im Auftrag hinterlegten (:2)',
    'creating' => 'Erzeuge',
    'creating_envia_contract' => 'Erzeuge envia-TEL-Vertrag :0',
    'cust_updated_by_order' => 'Kunde aktualisiert (envia-TEL-Auftrag :0)',
    'deleting' => 'Lösche',
    'description' => 'Beschreibung',
    'differentactivationdates' => 'Unterschiedliche Startdaten für die übergebenen Rufnummern (:0, :1)',
    'differentsubscriberdata' => 'Differences in subscriber data (:0 != :1)',
    'envia_contract_uptodate' => 'envia-TEL-Vertrag :0 ist aktuell',
    'envia_cust_id_is' => 'Die envia-TEL-Kundenreferenz ist :0.',
    'invalid_lines_in_csv' => 'Das gelieferte CSV enthält ungültige Zeilen:',
    'mgcp_not_implemented' => 'MGCP wird nicht unterstützt.',
    'misc_get_keys_unused' => 'Achtung: Diese Daten werden derzeit nicht zur Aktualisierung unserer Datenbank genutzt.',
    'misc_get_keys_warning' => 'Achtung: Die Daten für diese Schlüsselwerte sollen nicht öfter als einmal täglich heruntergeladen werden.',
    'new_number' => 'Neue Rufnummer',
    'no_management' => 'Kein PhonenumberManagement',
    'order_activation_date' => 'Aktivierungsdatum NMS',
    'order_activation_date_envia' => 'Aktivierungsdatum envia TEL',
    'order_active' => 'Aktiv',
    'order_address' => 'Adresse',
    'order_check_interaction' => 'Bitte prüfen, ob eine Bearbeitung notwendig ist.',
    'order_configfile' => 'Konfigurationsdatei',
    'order_contract' => 'Vertrag (= envia-TEL-Kunde)',
    'order_contract_end' => 'Vertragsende',
    'order_contract_id' => 'Vertrags-ID',
    'order_contract_reference' => 'envia TEL Vertragsreferenz',
    'order_contract_start' => 'Vertragsbeginn',
    'order_created_at' => 'Auftrag erstellt am',
    'order_customer_reference' => 'envia TEL Kundenreferenz',
    'order_deactivation_date' => 'Deaktivierungsdatum NMS',
    'order_deactivation_date_envia' => 'Deaktivierungsdatum envia TEL',
    'order_envia_tel_contract' => 'envia-TEL-Vertrag',
    'order_envia_tel_contract_id' => 'Vertragsreferenz',
    'order_fix' => 'fest',
    'order_get_status_error' => 'Fehler (HTTP-Status ist :0)',
    'order_get_status_success' => 'Erfolg (HTTP-Status ist :0)',
    'order_has_been_updated' => 'Auftrag wurde aktualisiert!',
    'order_has_internet' => 'Internet',
    'order_has_telephony' => 'Telefonie',
    'order_hostname' => 'Modemname',
    'order_id' => 'Auftrags-ID',
    'order_installation_address' => 'Installationsadresse',
    'order_items' => 'Posten (nur Internet and VoIP)',
    'order_last_status_update' => 'Letztes Status-Update',
    'order_last_user_interaction' => 'Letzte Bearbeitung',
    'order_list_all' => 'Liste aller envia-TEL-Aufträge',
    'order_list_interaction_needing' => 'Liste der envia-TEL-Aufträge, die eine Bearbeitung erfordern',
    'order_mac_address' => 'MAC-Adresse',
    'order_mark_as_solved' => 'Als bearbeitet markieren.',
    'order_method' => 'Methode',
    'order_modem' => 'Modem (pro Modem können mehrere envia-TEL-Verträge zugeordnet sein)',
    'order_modem_id' => 'Modem-ID',
    'order_number' => 'Nummer',
    'order_ordercomment' => 'Auftragskommentar',
    'order_orderdate' => 'Auftragsdatum',
    'order_orderstatus' => 'Auftragsstatus',
    'order_orderstatus_id' => 'Auftragsstatus-ID',
    'order_ordertype' => 'Auftragstyp',
    'order_ordertype_id' => 'Auftragstyp-ID',
    'order_phonenumber' => 'Telefonnummer',
    'order_phonenumber_id' => 'Telefonnummer-ID',
    'order_phonenumbers' => 'Telefonnummern',
    'order_product' => 'Produkt',
    'order_qos' => 'QoS',
    'order_related_order_created' => 'Zugehöriger Auftrag erstellt am',
    'order_related_order_deleted' => 'Zugehöriger Auftrag gelöscht am',
    'order_related_order_id' => 'ID des zugehörigen Auftrags',
    'order_related_order_last_updated' => 'Letztes Status-Update des zugehörigen Auftrags',
    'order_related_order_type' => 'Typ des zugehörigen Auftrags',
    'order_related_to_contract' => 'envia-TEL-Auftrag scheint sich auf einen Vertrag zu beziehen.',
    'order_related_to_customer' => 'envia-TEL-Auftrag scheint sich auf einen Kunden zu beziehen.',
    'order_related_to_nothing' => 'envia-TEL-Auftrag scheint eigenständig zu sein – keine Beziehung gefunden.',
    'order_related_to_phonenumber' => 'envia-TEL-Auftrag scheint sich auf eine Rufnummer zu beziehen.',
    'order_show_all' => 'zeige alle envia-TEL-Aufträge',
    'order_show_interaction_needing' => 'zeige nur envia-TEL-Aufträge, die eine Bearbeitung erfordern',
    'order_state' => 'Status',
    'order_type' => 'Typ',
    'order_valid_from' => 'Startdatum',
    'order_valid_to' => 'Endatum',
    'phonenumber_contract_ref_changed' => 'Die an der Rufnummer :0 hinterlegte envia-TEL-Vertragsreferenz (:1) unterscheidet sich von der eben gelieferten (:2). Ändere Eintrag.',
    'phonenumber_contract_ref_is' => 'Die envia-TEL-Vertragsreferenz für die Rufnummer :0 lautet :1',
    'phonenumber_contract_ref_new' => 'Keine envia-TEL-Vertragsreferenz an Rufnummer :0 – speichere :1',
    'phonenumber_has_no_management' => 'Der Rufnummer :0 ist kein PhonenumberManagement zugeordnet.',
    'phonenumber_n/a' => 'Die Rufnummer :0 existiert nicht in unserer Datenbank!',
    'phonenumber_needed_to_create_contract' => 'Zum Anlegen eines Anschlusses wird mindestens eine Rufnummer benötigt – es wurde aber keine übergeben.',
    'phonenumber_not_belongs_to_modem' => 'Rufnummer :0 gehört nicht zum Modem',
    'phonenumbermanagement_n/a_create_new' => 'Kein PhonenumberManagement für Rufnummer :0. Neues PhonenumberManagement wird angelegt – Sie müssen einige Werte händisch setzen!',
    'phonenumbermanagement_n/a_inactive_number' => 'Kein PhonenumberManagement für Rufnummer :0. Rufnummer ist inaktiv, darum wird kein PhonenumberManagement angelegt!',
    'phonenumbermanagement_n/a_trc' => 'Kein PhonenumberManagement für Rufnummer :0. Sperrklasse kann nicht gesetzt werden.',
    'ping_error' => 'Something went wrong.',
    'ping_success' => 'All works fine.',
    'removed_sip_domain_from_phonenumber' => 'SIP-Domain an Rufnummer :0 gelöscht (wird von envia TEL vergeben)',
    'removed_sip_username_from_phonenumber' => 'SIP-Nutzername an Rufnummer :0 gelöscht (wird von envia TEL vergeben)',
    'setting_external_cust_id' => 'Setze envia-TEL-Kundenreferenz für Vertrag :0 auf :1.',
    'sip_changed' => 'SIP-Daten für Rufnummer :0 aktualisiert.',
    'trc_changed' => 'Sperrklasse für Rufnummer :0 aktualisiert.',
    'updated' => ':a aktualisiert',
    'updating' => 'Aktualsiere',
    'updating_envia_contract' => 'Aktualisiere envia-TEL-Vertrag :0',
    'updating_envia_cust_in_envia_contract' => 'Ändere die envia-TEL-Kundenreferenz im envia-TEL-Vertrag :0 zu :1',
    'voice_data' => 'SIP/MGCP-Benutzerdaten für Modem-ID :0',
];
