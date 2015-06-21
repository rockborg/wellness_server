// javascript


function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}



var http = createRequestObject();

var httpReload = createRequestObject();

function sndReq(action,offset,verifica_modifica) {
	
	// test se si � in fase di modifica
	if(verifica_modifica && record_bloccato){
		
		// se non sono state operate modifiche sui campi sblocca senza chiedere nulla
		if(campi_mod.length==0){
			sndReqSblocca();
			record_bloccato=false;
		}
		else{
			// Mostra il CONFIRM
			var tralascia_modifiche = confirm(_("Warning")+"\n"+_('The record was not saved.')+"\n"+_('Discard changes?'));
			
			// sblocca e vai dove vuoi
			if(tralascia_modifiche){
				sndReqSblocca();
				record_bloccato=false;
			}
			else {
				return false;
			}
		}
		
	}
	
	/* Metti nella history i valori */
	
		/*var segnalibro = new Object();
			if(focusScheda){
				segnalibro.pos = "scheda";
			}
			else {
				segnalibro.pos = "tab";
			}
				segnalibro.counterHist=counter;            
				segnalibro.idRecordHist=idRecord;            
          		dhtmlHistory.add(segnalibro.pos,segnalibro);*/
	
     /* FINE HISTORY */
     
     // PRENDI LA CHIAVE PRIMARIA DEL RECORD
//    inputs=document.getElementsByTagName('input');
    inputs=$$('input');
		
	if(max==0){
		document.forms.singleform.reset();
		return false;
	}
	else if(!offset || offset=='min'){
		counter=0;
		stato_p(counter,max);
	}
	else if(offset=='next'){
		
		counter++;
		stato_p(counter,max);
	}
	else if(offset=='prev' && counter>0){
		counter--;
		stato_p(counter,max);
	}	
	else if(offset=='prev10' && counter>0){
		counter=(counter-passoVeloce);
		stato_p(counter,max);
	}	
	else if(offset=='next10' && counter<max){
		counter=(counter+passoVeloce);
		stato_p(counter,max);
	}
	else if(offset=='max'){
		counter=(max-1);
		stato_p(counter,max);
	}
	else if(offset=='manual'){
		
		counter=$('campo_goto').value - 1;
		if(counter>(max-1)){
			
			counter=(max-1);
		}
		else if((counter<1) || isNaN(counter)){
			counter=0;
		}
	}
	else if(offset=='id'){
		
		if(idRecord==0){
		
			idRecord=localIDRecord;
			
		}
		

	}
	
	// disattiva la ricerca
	if(ricerca){
		annulla_ricerca();
	}
	
	// disattiva le modifiche
	annulla_campi(false);
	
	
    /*http.open('GET', pathRelativo+'/rpc.php?action='+action+'&c='+counter+'&id='+idRecord+'&hash=' + Math.random());
// 	http.setRequestHeader("Content-Type", "text/plain; charset=utf-8");
    http.onreadystatechange = handleResponse;
    http.send(null);*/
    
    var url = pathRelativo+'/rpc.php?action='+action+'&c='+counter+'&id='+idRecord+'&hash=' + Math.random();

	new Ajax.Request(url, {
	  method: 'get',
	  onCreate: function() {
	  	$('refresh').update(' <img src="./img/refresh1.gif" width="12" heigth="12" alt="caricamento..." /> ' + _('Updating ...'));
	  },
	  onSuccess: function(transport) {
	  	 	var xml = transport.responseXML;

    	
		    // OPERAZIONI DI RESET	 ----------------------------------------------------------
		    	
		    	// Resetto i campi normali
		    	document.forms.singleform.reset();
		    	
		    	
		    	// Resetto gli evbentuali FCK editor
		    	if(fck_attivo && (fck_vars.length>0)){
		    		
		    		for(var f=0;f<fck_vars.length;f++){
		    			
		    			FCKeditorAPI.GetInstance("dati_"+fck_vars[f]).SetHTML('');
		    		}
		    	}
		    	
		    //---------------------------------------------------------------------------------
		    	
		    	
		    	
		    	var tag1= xml.getElementsByTagName("row").item(0);
		    	
		    	if(Number(idRecord)>0){
		
		    		// prendo l'attributo 'offset' del tag row
		    		var takedCounter = tag1.attributes[0].value;
					counter = takedCounter - 0;
						 
					annulla();
		    	}
		    	
		    	var n_nodi_provvisorio = tag1.childNodes.length;
		    	var n_nodi=0;
		    	var nomi_nodi=new Array();
				
				for(i=0;i<n_nodi_provvisorio;i++){
		
					// la condizione � per Mozilla
					if(tag1.childNodes[i].nodeName!='#text'){
						n_nodi++;
						nomi_nodi[nomi_nodi.length]=tag1.childNodes[i].nodeName;
					}
					
				}
					
					for(i=0;i<n_nodi;i++){
					
						
						try{
							valore='';
							nome_nodo = nomi_nodi[i];
							var puntatore = xml.getElementsByTagName(nome_nodo).item(0);
							valore=(puntatore.firstChild.data) ? puntatore.firstChild.data : '';
							
							if($("pk_"+nome_nodo)){
								
								$("pk_"+nome_nodo).value=valore;
								
								// IMPOSTA LA CHIAVE PRIMARIA DEL RECORD
								localIDRecord=valore;
							}
							else if($("dati_"+nome_nodo).className=='onlyread-field'){
								
								$("dati_"+nome_nodo).update(valore+' ');
							}
							
							// Esclusione per i campi hidden
							else if($("dati_"+nome_nodo) && $("dati_"+nome_nodo).className!='nomodify'){
							
								// Attribuzione generale
								$("dati_"+nome_nodo).value=valore;
								
								// CONDIZIONE PER I CHECKBOX
								if($("dati_"+nome_nodo).type=='checkbox'){
									
									if($("dati_"+nome_nodo).value=='1' || (PGdb==true && $("dati_"+nome_nodo).value=='t')){
										$("dati_"+nome_nodo).checked=true;
									}
									else{
										$("dati_"+nome_nodo).checked=false;
									}
									
								}
								if(fck_attivo && fck_vars.inArray(nome_nodo)){
									
									FCKeditorAPI.GetInstance("dati_"+nome_nodo).SetHTML(valore);
									attiva_modifica_fck(FCKeditorAPI.GetInstance("dati_"+nome_nodo),nome_nodo);
								}
							}
						}
						catch(e){ /* xmlError(e); */ }
					}
		    	
					
				idRecord=0;
				
				// prendi le sottomaschere
				if(sottomaschere.length>0){
					richiediSUB();
				}
				
				
				// richiama gli allegati e i link se impostati
				if(permettiAllegati!=0 || permettiLink!=0){
					
					richiediAL();
				}
				
				stato_p(counter,max);
				$('refresh').update('');	
	  }
	 });
	
    
}

function richiediAL(){
	
	  // Chiamata per AL (allegati & link)
	  httpAL = createRequestObject();
	  httpAL.open('GET', pathRelativo+'/rpc.allegati_link.php?action='+ tabella_alias +'&id='+localIDRecord+'&hash=' + Math.random(),true);
	  httpAL.onreadystatechange = handleResponseAL;
	  httpAL.send(null);
	
}


function richiediSUB(){
	
	  // Chiamata per AL (allegati & link)
	  httpSUB = createRequestObject();
	  var  stringaSUB=	pathRelativo+'/rpc.subcount.php?action='+ tabella +'&id='+localIDRecord+'&subs=' + sottomaschere.join('|') + '&hash=' + Math.random();
	  httpSUB.open('GET', stringaSUB,true);
	  httpSUB.onreadystatechange = handleResponseSUB;
	  httpSUB.send(null);
	
}




function handleResponseAL(){
	
	
	if(httpAL.readyState == 4){
		if(httpAL.status == 200){
	        
	    	var AL = httpAL.responseText;
	    	
	    	// aggiorna i campi
	    	var arrayAL= AL.split(",");
	    	
		   	// allegati
	    	if(permettiAllegati==1){
		    	if(arrayAL[0]>0){
		    		$('href_tab_allegati').innerHTML='<strong>'+_('attachments')+' ('+arrayAL[0]+')</strong>';
		    	}
		    	else{
		    		$('href_tab_allegati').innerHTML=_('attachments')+' (0)';
		    	}
	    	}    	
	    	
	    	// Link
	    	if(permettiLink==1){
		    	if(arrayAL[1]>0){
		    		$('href_tab_link').innerHTML='<strong>'+_('link')+' ('+arrayAL[1]+')</strong>';
		    	}
		    	else{
		    		$('href_tab_link').innerHTML=_('link')+' (0)';
		    	}
	    	}
	    	
	    	
	    	$('refresh').innerHTML='';
		}
		else{
			
			alert(_('Error Loading RPC request. Status:')+httpAL.status);
		}
	}
}




function handleResponseSUB(){
	
	
	if(httpSUB.readyState == 4){
		if(httpSUB.status == 200){
	        
	    	var SUB = httpSUB.responseText;
	    	
//	    	alert(SUB);
	    	
	    	// aggiorna i campi
	    	var arraySUB= SUB.split(",");
	    	
	    	
	    	for(i=0;i<sottomaschere.length;i++){
	    	
	    		var val_sub = ((arraySUB[i]-0)>0) ? " ("+arraySUB[i]+")" : "";
	    			
	    		$('sm_'+sottomaschere[i]).style.fontWeight= ((arraySUB[i]-0)>0) ? 'bold' : 'normal';
	    		$('sm_'+sottomaschere[i]).value = sottomaschere_alias[i] + val_sub;
	    	}
	    	
	    	
	    	$('refresh').innerHTML='';
		}
		else{
			
			alert(_('Error Loading RPC request. Status:')+httpSUB.status);
		}
	}
}


function pulisci_SUB(){
	
	for(i=0;i<sottomaschere.length;i++){
	    	
	    		$('sm_'+sottomaschere[i]).style.fontWeight='normal';
	    		$('sm_'+sottomaschere[i]).value = sottomaschere_alias[i];
	    	}
}





function sndReqUpdate(action) {
		
	
    http.open('POST',  pathRelativo+'/rpc.php?post=update&action='+action+'&c='+counter, true);
    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    http.onreadystatechange = handleResponsePostUpdate;

    inputs = document.getElementsByTagName('input');
	
    var post_string='';
    
	for(j=0;j<inputs.length;j++){
		
		if(inputs[j].id.substring(0,3)=="pk_"){
			post_string += inputs[j].name + "=";
			post_string += inputs[j].value + "&";
		}
		
		// aggiungi gli hidden
		if(inputs[j].id.substring(0,5)=="dati_" && inputs[j].type=='hidden'){
			campi_mod[campi_mod.length]=inputs[j].id;
			
			
			if(inputs[j].className=='nomodify'){
				
				// Impostazioni per gli hidden DEFAULT
				var span_hidden='hd_'+inputs[j].id;
				variabile_hidden= $(span_hidden).innerHTML;
				
				inputs[j].value=variabile_hidden;
			}
		}
		
		
	}
	
	
	// AGGIUNGI GLI EVNTUALI FCK
	if(fck_attivo){
			for(i=0;i<fck_vars.length;i++){
				
				editorfck = FCKeditorAPI.GetInstance('dati_'+fck_vars[i]);
				
				// attribuisco all'hidden che si chiama come il fckeditor il valore assunto dallo stesso
				$('dati_'+fck_vars[i]).value=editorfck.GetXHTML();
			}
	}
	
	
	for(g=0;g<campi_mod.length;g++){
		
			// opzioni per i campi password
			if($(campi_mod[g]).type=='password'){
				
				if($(campi_mod[g]).type=='password' 
					&& $(campi_mod[g]).title=='md5' 
					&& $(campi_mod[g]).value.length!=32
					&& $(campi_mod[g]).value.length>0
					) {
					
					$(campi_mod[g]).value = hex_md5($F(campi_mod[g]));
				}
				else if($(campi_mod[g]).type=='password' 
					&& $(campi_mod[g]).title=='sha1' 
					&& $(campi_mod[g]).value.length!=40
					&& $(campi_mod[g]).value.length>0
					) {
					
					$(campi_mod[g]).value = hex_sha1($F(campi_mod[g]));
				}
			}
		
			post_string += $(campi_mod[g]).name + "=";
			post_string += escape($(campi_mod[g]).value) + "&";
		}
	
    http.send(post_string);
}



/*function attribuisci_valori_hidden(valoreHidden,campoHidden){
	
	switch(valoreHidden){
		case '%nome': campoHidden.value=varHidden['nome']; break;
		case '%cognome': campoHidden.value=varHidden['cognome']; break;
		case '%cognomenome': campoHidden.value=varHidden['cognomenome'] + " " + varHidden['nome']; break;
		case '%nomecognome': campoHidden.value=varHidden['nome'] + " " + varHidden['cognomenome']; break;
		case '%gid': campoHidden.value=varHidden['gid']; break;
		case '%gid': campoHidden.value=varHidden['gid']; break;
	}
	
}*/



function sndReqPostNew(action) {
		
	
    http.open('POST',  pathRelativo+'/rpc.php?post=new&action='+action, true);
    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    http.onreadystatechange = handleResponsePostNew;

    inputs = document.getElementsByTagName('input');
	
    var post_string='';
    
  
	for(j=0;j<inputs.length;j++){
		
		if(inputs[j].id.substring(0,3)=="pk_"){
			post_string += inputs[j].name + "=";
			post_string += inputs[j].value + "&";
		}
		
		
		
    
		// aggiungi gli hidden
		if(inputs[j].id.substring(0,5)=="dati_" && inputs[j].type=='hidden'){
			campi_mod[campi_mod.length]=inputs[j].id;

			// Impostazioni per gli hidden DEFAULT
			var span_hidden='hd_'+inputs[j].id;
			try{
			variabile_hidden= $(span_hidden).innerHTML;
			inputs[j].value=variabile_hidden;
			}
			catch(e){}
		}
	}
	
	// AGGIUNGI GLI EVNTUALI FCK
	if(fck_attivo){
			for(i=0;i<fck_vars.length;i++){
				
				editorfck = FCKeditorAPI.GetInstance('dati_'+fck_vars[i]);
				
				// attribuisco all'hidden che si chiama come il fckeditor il valore assunto dallo stesso
				$('dati_'+fck_vars[i]).value=editorfck.GetXHTML();
			}
	}
	
	for(g=0;g<campi_mod.length;g++){
		
			// opzioni per i campi password
			if($(campi_mod[g]).type=='password'){
				
				if($(campi_mod[g]).type=='password' 
					&& $(campi_mod[g]).title=='md5' 
					&& $(campi_mod[g]).value.length!=32
					&& $(campi_mod[g]).value.length>0
					) {
					
					$(campi_mod[g]).value = hex_md5($F(campi_mod[g]));
				}
				else if($(campi_mod[g]).type=='password' 
					&& $(campi_mod[g]).title=='sha1' 
					&& $(campi_mod[g]).value.length!=40
					&& $(campi_mod[g]).value.length>0
					) {
					
					$(campi_mod[g]).value = hex_sha1($F(campi_mod[g]));
				}
			}
		
			post_string += $(campi_mod[g]).name + "=";
			post_string += escape($(campi_mod[g]).value) + "&";
		}
		
    http.send(post_string);
}




function sndReqPostCerca(action) {
		
	if(campi_mod.length>0){
	
	    http.open('POST', pathRelativo+'/rpc.php?post=cerca&action='+action, true);
	    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	    http.onreadystatechange = handleResponsePostCerca;
	
	    var post_string='';
	    
		for(g=0;g<campi_mod.length;g++){
				post_string += $(campi_mod[g]).name + "=";
				post_string += $(campi_mod[g]).value + "&";
			}
		
	    http.send(post_string);
	}
	else{
		setStatus(_('No search was attempted! <br/> Enter values in at least one field'),3500,'risposta-arancio');
	}
}





function sndReqPostDelete(action) {
		
	
    http.open('POST',  pathRelativo+'/rpc.php?post=delete&action='+action, true);
    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    

    inputs = document.getElementsByTagName('input');
	
    var post_string='';
    
	for(j=0;j<inputs.length;j++){
		
		if(inputs[j].id.substring(0,3)=="pk_"){
			post_string += inputs[j].name + "=";
			post_string += inputs[j].value + "&";
		}
	}
	
	http.onreadystatechange = handleResponsePostDelete;
    http.send(post_string);
}




function sndReqPostDuplica(action,oid_sub,duplica_allegati,duplica_link) {
		
	
    http.open('POST',  pathRelativo+'/rpc.php?post=duplica&action='+action+"&oid_sub="+oid_sub+"&da="+duplica_allegati+"&dl="+duplica_link, true);
    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    

    inputs = document.getElementsByTagName('input');
	
    var post_string='';
    
	for(j=0;j<inputs.length;j++){
		
		if(inputs[j].id.substring(0,3)=="pk_"){
			post_string += inputs[j].name + "=";
			post_string += inputs[j].value + "&";
		}
	}
	
	http.onreadystatechange = handleResponsePostDuplica;
    http.send(post_string);
}






// Prova a bloccare un record in caso di modifica
// se � gi� bloccato impedisce la modifica
function sndReqBlocca() {
		
	inputs = document.getElementsByTagName('input');
	
	for(j=0;j<inputs.length;j++){
		
		if(inputs[j].id.substring(0,3)=="pk_"){
			colonna = inputs[j].id.substring(3);
			id = inputs[j].value;
		}
	}
	
    http.open('GET', pathRelativo+'/rpc.recordlock.php?tab='+tabella+'&col='+colonna+'&id='+id+'&blocca=1&hash=' + Math.random());
    http.onreadystatechange = handleResponseBlocca;
    http.send(null);
}






function handleResponseBlocca(){
	if(http.readyState == 4){
	 	var esito_sql = http.responseText
	 	
	 	if(esito_sql==1){
	 		
	 		attiva_campi('modifica');
			tipo_salva="modifica";
	 	}
			
		else{
			setStatus(_('This record is being edited by another user <br/> Please try again later'),4200,'risposta-arancio');
			
		}
	 }
	
}







// Prova a Sbloccare un record in caso di annullamento o salvataggio
function sndReqSblocca() {
		
	var post_string='';
    
	inputs = document.getElementsByTagName('input');
	
	for(j=0;j<inputs.length;j++){
		
		if(inputs[j].id.substring(0,3)=="pk_"){
			colonna = inputs[j].id.substring(3);
			id = inputs[j].value;
		}
	}
	
    http.open('GET',  pathRelativo+'/rpc.recordlock.php?tab='+tabella+'&col='+colonna+'&id='+id+'&sblocca=1&hash=' + Math.random(),false);
    http.onreadystatechange = handleResponseSblocca;
    http.send(null);
}





function handleResponseSblocca(){
	if(http.readyState == 4){
	 	var esito_sql = http.responseText
	 	
	 	if(esito_sql==1){
	 		
	 		record_bloccato=false;
	 	}
	 }
	
}














function stato_p(counter,max){
	
	var p_primo=$('p_primo');
	var p_prev=$('p_prev');
	var p_prev10=$('p_prev10');
	var p_next=$('p_next');
	var p_next10=$('p_next10');
	var p_max=$('p_ultimo');
	
	if(counter==0){
		p_primo.disabled=true;
		p_prev.disabled=true;
		p_prev10.disabled=true;
	}
	else{
		p_primo.disabled=false;
		p_prev.disabled=false;
		
	}
	
	
	if(counter-passoVeloce<0){
		p_prev10.disabled=true;
	}
	else{
		p_prev10.disabled=false;
	}
	
	
	// next
	if(counter+passoVeloce>=max){
		p_next10.disabled=true;
	}
	else{
		p_next10.disabled=false;
	}
	
	if(counter>=(max-1)){
		p_max.disabled=true;
		p_next.disabled=true;
		p_next10.disabled=true;
	}else{
		p_max.disabled=false;
		p_next.disabled=false;
	}
	
	modifiche_attive=false;
	$('p_save').disabled=true;
	$('p_annulla').disabled=true;
	$('p_update').disabled=false;
	
	$('numeri').update(_('Record')+' <span id="goto" ondblclick="goto1();">'+(counter+1)+'</span> '+_('of')+' '+max);
	
}



function handleResponsePostUpdate(){
	 if(http.readyState == 4){
	 	var risposta_sql = http.responseText;
	 	
	 	if(risposta_sql==1){
//	 		$('risposta').className='risposta-giallo';
//			$('risposta').innerHTML='Record modificato correttamente';
			setStatus(_('Record updated correctly'),3500,'risposta-giallo');
			campi_mod = new Array();
		}
		else{
//			$('risposta').className='risposta-arancio';
//			$('risposta').innerHTML='Errore nella modifica del record';
			setStatus(_('Error updating record'),3500,'risposta-arancio');
		}
	 }
}

function handleResponsePostNew(){
	 if(http.readyState == 4){
	 	var risposta_sql = http.responseText;
	 	
	 	if(risposta_sql>0){
	 		
	 		// cambia il valore di max
	 		max=max+1;
	 		// aggiorna i contatori ed i pulsanti
	 		stato_p();
	 		
	 		// imposta l'idRecord
	 		idRecord = risposta_sql-0;
	 		
	 		
	 		sndReq(tabella,'id',false);
	 		
	 		// se � una scheda "nuovo valore"
	 		if(haveParent){
	 			
	 			// Manda la richiesta di aggiornamento della tendina...
	 			hash_aggiornato=false;
	 			httpReload.open('POST',  pathRelativo+'/rpc.refresh_iframe.php');
			    httpReload.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			    httpReload.onreadystatechange = handleReqRicaricaTendina;
			    httpReload.send('tabella='+parentTable+'&campo='+parentField);
	 			
	 			// se � in modalit� modifica nel parent proponi l'ultimo valore
	 			
	 		}
	 		
			setStatus(_('Record inserted correctly'),3500,'risposta-giallo');
			campi_mod = new Array();
		}
		else{
			setStatus(_('Error inserting record'),3500,'risposta-arancio');
		}
	 }
}



function handleReqRicaricaTendina(){
	
	
	if(httpReload.readyState == 4){
		
	 	hash_aggiornato = httpReload.responseText;
	 	
	 	window.opener.document.getElementById('i_id_'+parentField).src='files/html/'+hash_aggiornato+'.html';
	 	
	 	window.opener.setStatus(_('Values of dropdown list')+ ' ' + parentField+' '+_('updated'),6500,'risposta-giallo');
	 	
	 	
	 	setTimeout( "self.close()",800);
	 	window.opener.focus();
	}	
}
















/* FUNZIONI DEL DOM JS */

function handleResponsePostDelete(){
	 if(http.readyState == 4){
	 	var risposta_sql = http.responseText;

		if(risposta_sql==1){
			
			// cambia il valore di max
	 		max=max-1;
	 		
	 		if(max==0){
				inizializza_pulsanti_modifica();
				sndReq(tabella,'prev',false);
	 		}
	 		else{
	 		// aggiorna i contatori ed i pulsanti
	 			stato_p();
				sndReq(tabella,'prev',false);
	 		}
	 		
//			$('risposta').className='risposta-giallo';
			setStatus(_('Record deleted correctly'),3500,'risposta-giallo');
		}
		else if((risposta_sql-0)>1){
			
			erroreDB = erroreDBNum(risposta_sql);
			setStatus(erroreDB,3500,'risposta-arancio');
		}
		else{
//			$('risposta').className='risposta-arancio';
			setStatus(_('Can not delete record'),3500,'risposta-arancio');
		}
	 }
}




function handleResponsePostDuplica(){
	 if(http.readyState == 4){
	 	var risposta_sql = http.responseText;
	 	var array_ris_duplica=risposta_sql.split("|");
	 	
	 	if(array_ris_duplica[0]==1){
			
			// cambia il valore di max
	 		max=max+1;
	 		
	 		var IDduplicato = array_ris_duplica[1]-0;
	 		sndReq(tabella,'id',false);
	 		
	 		$('popup-duplica').style.display='none';
	 		
	 		setStatus(_('Record duplicated correctly')+'. <a href="javascript:;" onclick="idRecord='+IDduplicato+';sndReq(tabella,\'id\',false);">'+_('Go to duplicated record')+'</a>',10000,'risposta-giallo');
	 		
	 	}
	 	else{
			setStatus(_('Can not update record'),3500,'risposta-arancio');
		}
	 }
}



function handleResponsePostCerca() {

 	if(http.readyState == 4){
	 	var risposta_sql = http.responseText;

	 	var nRisultati = 0;
	 	var risultatiRicerca= new Array();
		
	 	if(risposta_sql.length==0){
	 		nRisultati=0;
	 		// Scrivi il messaggio
	 		
	        setStatus(_('No records found by this search'),3500,'risposta-verdino');
	        
	 		
	 	}
	 	else if(risposta_sql.indexOf("|")==-1 && !isNaN(risposta_sql)){
	 		nRisultati=1
	 		idRecord=risposta_sql;
	 		sndReq(tabella,'id',false);
	 		
	        setStatus(_('1 record found and shown'),3500,'risposta-verdino');
	 	}
	 	else{
	 	 	risultatiRicerca=risposta_sql.split("|");
			nRisultati = risultatiRicerca.length;
			
	        setStatus(nRisultati+' '+_('records found for this search'),3500,'risposta-verdino');
	        mostra_risultati_ricerca(risposta_sql);
	        annulla_ricerca();
	 	}
		
		
		
	 }
}




/* FUNZIONI DEL DOM JS */



function xmlError(e) {
	//there was an error, show the user
	alert(e);
} //end function xmlError



/* FUNZIONI PER L'INIZIALIZZAZIONE */

function inizializza_pulsanti_modifica(){
	
		if((tendineAttese-nTendine)==0 && (!fck_attivo || fck_pronti==fck_vars.length)){
			inizializza_scheda();
		
		}
		
	// altrimenti aspetta che le tendine siano caricate ed esegui le operazioni
}

function triggerLoadTendina(){
		
		nTendine++;
		if((tendineAttese-nTendine)==0 && (!fck_attivo || fck_pronti==fck_vars.length)){
			
			inizializza_scheda();
		}
}


function FCKeditor_OnComplete( editorInstance ){
	  
	if(editorInstance.Name){
    	fck_pronti++;
    }
    
    if(fck_pronti==fck_vars.length && (tendineAttese-nTendine)==0 ){		
   
//    	alert('tutti gli FCK caricati'); 
    	inizializza_scheda();	
    }
    
}

function triggerFCK(){
	
	
}


function inizializza_scheda(){

		// nascondi i div preloader
		
			$('loader-scheda0').removeChild($('pop-loader-contenitore'));
			$('loader-scheda0').removeChild($('loader-scheda'));
	
		if(max==0){
			$('p_update').disabled=true;
			$('numeri').update(_('There are no records in this table'));
			
		}
		else{
			if(counter==0){
				sndReq(tabella,'min',false);	
			}
			else{
				sndReq(tabella,'id',false);	
			}
			
		}
		
		$('p_save').disabled=true;
		$('p_annulla').disabled=true;
		
		initScheda=true;
		

}







function attiva_campi(classe){
	
	if(classe=='ricerca'){
		cn='s';
	}
	else {
		cn='on';
	}
	
	inputs = document.getElementsByTagName('input');
	textareas = document.getElementsByTagName('textarea');
	selects = document.getElementsByTagName('select');
	
	for(j=0;j<inputs.length;j++){
		if(inputs[j].id.substring(0,5)=="dati_" && inputs[j].type!='hidden' && inputs[j].type!='checkbox'){
			inputs[j].readOnly=false;
			inputs[j].className=cn;
		}
		else if(inputs[j].id.substring(0,5)=="dati_" && inputs[j].type=='checkbox'){
			inputs[j].disabled=false;
			inputs[j].className=cn;
			if(PGdb==true){
				inputs[j].value= (inputs[j].checked==true) ? 't':'f'; 
			}
			else{
				inputs[j].value= (inputs[j].checked==true) ? 1:0; 
			}
		}
	}
	
	for(j=0;j<textareas.length;j++){
		if(textareas[j].id.substring(0,5)=="dati_"){
			textareas[j].readOnly=false;
			textareas[j].className=cn;
		}
	}
	
	for(j=0;j<selects.length;j++){
		if(selects[j].id.substring(0,5)=="dati_"){
			selects[j].disabled=false;
			selects[j].className=cn;
		}
	}
	
	// FCK
	if(fck_attivo){
		for(i=0;i<fck_vars.length;i++){
			
			if(classe=='ricerca'){
				FCKeditorAPI.GetInstance("dati_"+fck_vars[i]).SetHTML('');
			}
			
			attiva_fck('dati_'+fck_vars[i]);
		}
	}
	
	
	$('p_update').disabled=true;
	$('p_annulla').disabled=false;
	
	
}

function disattiva_campi(){
	
	inputs = document.getElementsByTagName('input');
	textareas = document.getElementsByTagName('textarea');
	selects = document.getElementsByTagName('select');
	
	for(j=0;j<inputs.length;j++){
		if(inputs[j].id.substring(0,5)=="dati_"){
			
			if(inputs[j].type=='checkbox'){
				inputs[j].disabled=true;
			}
			else{
				inputs[j].readOnly=true;
			}
			
			// eccezione per gli hidden
			if(inputs[j].className!='nomodify'){
				inputs[j].className='off';
			}
			
		}
	}
	
	for(j=0;j<textareas.length;j++){
		if(textareas[j].id.substring(0,5)=="dati_"){
			textareas[j].readOnly=true;
			textareas[j].className='off';
		}
	}
	
	for(j=0;j<selects.length;j++){
		if(selects[j].id.substring(0,5)=="dati_"){
			selects[j].disabled=true;
			selects[j].className='off';
		}
	}
	
	// FCK
	if(fck_attivo){
		for(i=0;i<fck_vars.length;i++){
			blocca_fck('dati_'+fck_vars[i]);
		}
	}		
}


function modifica(){
	
	record_bloccato=true;
	modificaRecord=true;
	sndReqBlocca();

}


function annulla_ricerca(){

		ricerca=false;
		
		$('p_cerca').value=" "+_(' Search ')+" ";
		$('p_cerca').className='';
		$('p_cerca').onClick='cerca();';
				
		$('p_annulla').disabled=true;
		$('p_insert').disabled=false;
		$('p_delete').disabled=false;
}


function annulla(){
	
	
	if(ricerca){
		annulla_ricerca();
	}
	else{
		sndReqSblocca();	
	}
	
	campi_mod= new Array();
	
	annulla_campi(true);
}

function annulla_campi(manda){
	
	if(manda){
		sndReq(tabella,counter,false);
	}
	
	
	
	disattiva_campi();
	
	modifiche_attive=false;
	
	nuovoRecord=false;
	modificaRecord=false;
	
	$('p_save').disabled=true;
	$('p_annulla').disabled=true;
	$('p_update').disabled=false;
	
	// annulla feedback risposta
	/*$('risposta').className='';
	$('risposta').innerHTML='&nbsp;';*/
	
}


function mod(id){
	if(!ricerca){
		modifiche_attive=true;
		$('p_save').disabled=false;

	}
	else{
		// cattura della pressione di invio per la ricerca
		if(window.event){
			k = (window.event) ? window.event.keyCode : id.which;
			if(k==13) {
				invia_ricerca();
			}
		}
	}
	
	$('p_annulla').disabled=false;
	
	trovato=false;
	
	// se non c'� gia'
	for(t=0;t<campi_mod.length;t++){
		if(campi_mod[t]==id){
			trovato=true;
		}
	}
	
	if(!trovato){
		campi_mod[campi_mod.length]=id;
	}
}

function modfck(ofck){
	if(modificaRecord){
		mod(ofck.Name);
	}
}

function salva(){
	
	msg = controlla_dati(campiReq);
		
	if(msg!=''){
		alert (_("Warning!")+"\n"+msg);
		return false;
	}
	
	else{
		
		// Controllo sui campi YAV 
		if(jstest){
			test_yav= performCheck('singleform', rules, 'classic');
			if(!test_yav) return false;
		}
	
		if(tipo_salva=='modifica'){
			sndReqSblocca();
			sndReqUpdate(tabella);
			modificaRecord=false;
		}
		else if(tipo_salva=='nuovo'){
			sndReqPostNew(tabella);
			nuovoRecord=false;
		}
		modifiche_attive=true;
		$('p_save').disabled=true;
		$('p_annulla').disabled=true;
		
		annulla_campi(false);
	}
}


function nuovo_record(){
	
	$('p_save').disabled=false;
	$('p_annulla').disabled=false;
	$('p_update').disabled=true;
	
	
	
	f=document.forms.singleform;
	
	f.reset();
	
	attiva_campi('nuovo');	
		
	tipo_salva="nuovo";
	
	nuovoRecord=true;
	
	pulisci_SUB();
	
	
	// Metti una variabile 'new' su localIDRecord utile per allegati e link
	localIDRecord = 'new';
	richiediAL();
	
	
	if(fck_attivo){
		for(i=0;i<fck_vars.length;i++){
			
			FCKeditorAPI.GetInstance('dati_'+fck_vars[i]).EditorDocument.body.innerHTML='';
		}
	}
}



function cerca(){
	
	if(ricerca){
		invia_ricerca();
	}
	
	
	$('p_save').disabled=true;
	$('p_annulla').disabled=false;
	$('p_update').disabled=true;
	$('p_insert').disabled=true;
	$('p_delete').disabled=true;
	
	inputs = document.getElementsByTagName('input');
	textareas = document.getElementsByTagName('textarea');
	
	f=document.forms.singleform;
	
	f.reset();
	
	attiva_campi('ricerca');
	
	// Metti una variabile 'new' su localIDRecord utile per allegati e link
	localIDRecord = 'ric';
	richiediAL();
	
	ricerca =true;
	
	$('p_cerca').value=_('Start search');
	$('p_cerca').className='var';
}



function invia_ricerca(){
	
	sndReqPostCerca(tabella);
}


function elimina(){
	if(confirm(_('Do you really want to delete this record? This operation cannot be undone.'))){
		sndReqPostDelete(tabella);
	}
}


function duplica(){
	if(confirm(_('Do you really want to duplicate the record? Data on subforms and any attachments and links will not be duplicated automatically.'))){
		sndReqPostDuplica(tabella);
	}
}

function prepara_duplica(){
		
		var mydiv=$('popup-duplica');
		
		var arg_sub='';
		var DA=0;
		var DL=0;
		
		
		ii=mydiv.getElementsByTagName('input');
		
		for(i=0;i<ii.length;i++){
			
			if(ii[i].name.substr(0,7)=='sotto__' && ii[i].checked==true){
				arg_sub+=ii[i].name.substr(7)+'_';
			}
			
			else if(ii[i].name=='duplica_allegati' && ii[i].checked==true){
				
				DA=1;
			}
			else if(ii[i].name=='duplica_link' && ii[i].checked==true){
				
				DL=1;
			}
			
		}
		
	sndReqPostDuplica(tabella,arg_sub,DA,DL);
		
}


function debug_xml(){
	
	alert(http.responseText);
}



function controlla_dati(campi_obb){
	
	var msg_controllo='';
	var errore = false;
	
	for(i=0;i<campi_obb.length;i++){
		
		nome_campo = "dati_"+campi_obb[i];
		
		if($(nome_campo).value=='' || $(nome_campo).value==null){
			msg_controllo+= _('The field')+ campi_obb[i] +' '+_('must be completed')+'\n';
			errore=true;
		}
	}
	
	return msg_controllo;
	
}


function debug_var(){
	
	
	var debugVar="counter: "+counter;
		
		debugVar+="\nmax: "+max;
		
		debugVar+="\nmodifiche_attive: "+modifiche_attive;
		
		debugVar+="\nricerca :"+ricerca;
		
		debugVar+="\ncampi_mod: Array "+campi_mod.join("|");
		
		debugVar+="\ntipo_salva: "+tipo_salva;
		
		debugVar+="\ncampiReq: Array "+campiReq.join("|");
		
		debugVar+="\ncampiSearch: Array "+campiSearch.join("|");
		
		debugVar+="\ncampiSuggest: Array "+campiSuggest.toString();
		
		debugVar+="\nrecord_bloccato: "+record_bloccato;
		
		debugVar+="\nidRecord: "+idRecord;
		
		debugVar+="\nlocalIDRecord: "+localIDRecord;
		
		debugVar+="\nfocusScheda: "+focusScheda;
		
		debugVar+="\ninitGrid: "+initGrid;
		
		debugVar+="\nnuovoRecord: "+nuovoRecord;
		
		debugVar+="\nmodificaRecord: "+modificaRecord;
		
		debugVar+="\nhaveParent: "+haveParent;
		
		alert(debugVar);
}

/* Funzioni RICO */
 		
			/*new Rico.Effect.Round('div', 'roundme' );
	      setStatus('consider yourself well rounded ;)',1500);*/
 		
 		
   function setStatus(messaggio,tempo,classe) {
      $('feedback').style.visibility = "visible";
      $('risposta').innerHTML = messaggio;
      $('risposta').className = classe;
      setTimeout( "$('feedback').style.visibility = 'hidden'; ", tempo );
   }
   
   



function erroreDBNum(n){
	
	n=n-0;
	
	// Codici Errori di Postgres
	if(PGdb){
		
		if(n==1451){
			return _('Can not delete the record <br/> there are related records');
		}
		else if(23505){
			return _('Can\'t add record - Duplicate key');
		}	
		else if(23503){
			return _('Impossibile aggiungere il record<br/>Non esiste la referenzialit&#224; alla tabella collegata');
		}	
		else if(1345){
			return _('Can not delete from join view');
		}
		else{
			return _('Can not do this <br/> (Error Code:')+n+')';
		}
	}
	// Codici Errori di MYSQL
	else{
		
		if(n==1451){
			return _('Can not delete the record <br/> there are related records');
		}
		else if(1022){
			return _('Can\'t add record - Duplicate key');
		}	
		else if(1452){
			return _('Impossibile aggiungere il record<br/>Non esiste la referenzialit&#224; alla tabella collegata');
		}	
		else if(1345){
			return _('Can not delete from join view');
		}
		else{
			return _('Can not do this <br/> (Error Code:')+n+')';
		}
	}
	
}


function goto1(){
	
	
	attuale_numero = $('goto').innerHTML;
	if(!isNaN(attuale_numero-0)){
		$('goto').innerHTML='<input type="text" class="micro" size="5" name="campo_goto" id="campo_goto" value="'+attuale_numero+'" onkeypress="return noNumbers(event)"/></form>';
	}

	$('campo_goto').focus;
	
}



function noNumbers(e)
{
	var keynum
	var keychar
	var numcheck
	
	if(window.event){ // IE
	
		keynum = e.keyCode
	}
	else if(e.which){ // Netscape/Firefox/Opera
	
		keynum = e.which
	}
	
//	alert(keynum);
	if(keynum==13){
		
		if($('campo_goto').value.substring(0,3)=='id:'){
			// apre il record di id:
			idRecord=$('campo_goto').value.substring(3);
			sndReq(tabella,'id',false);
		}
		else{
		// apre il record numero:
			sndReq(tabella,'manual',false);
		}
	}
	/*keychar = String.fromCharCode(keynum)
	numcheck = /\d/
	return !numcheck.test(keychar)*/
}

function catturaInvio(e)
{
	var keynum
	var keychar
	var numcheck
	
	if(window.event){ // IE
	
		keynum = e.keyCode
	}
	else if(e.which){ // Netscape/Firefox/Opera
	
		keynum = e.which
	}
	
//	alert(keynum);
	if(keynum==13){
		
		invia_ricerca();
	}
}


function switch_vista(){
		
		if(focusScheda){
	
			// Switch:
			$('scheda1').style.display='none';
			$('scheda-tabella').style.display='';
		
			$('p_prev').style.display='none';
			$('p_next').style.display='none';
			
			// Inizializza la tabella
			focusScheda=false;
			
			if(!ricerca && max>0){
	 			caricaGrid();
			}
			
			if(usaHistory){
			
				var segnalibro = new Object();
	      		segnalibro.pos = "tab";
				segnalibro.counterHist=counter;            
				segnalibro.idRecordHist=idRecord;            
	      		dhtmlHistory.add("tab",segnalibro);
			}
		}
		else{
		
		// Switch:
			$('scheda1').style.display='';
			$('scheda-tabella').style.display='none';
		
			$('p_prev').style.display='';
			$('p_next').style.display='';
			
			focusScheda=true;
			
			if(usaHistory){
				
				var segnalibro = new Object();
	      		segnalibro.pos = "scheda";
				segnalibro.counterHist=counter;            
				segnalibro.idRecordHist=idRecord;            
	      		dhtmlHistory.add("scheda",segnalibro);
			}
	}
		
}



// FUNZIONI DI HISTORY

    
	function history_initialize() {

		  // initialize the DHTML History framework
		  dhtmlHistory.initialize();
		
		  
		  // subscribe to DHTML history change events
		  dhtmlHistory.addListener(historyChange);
		  
		  var segnalibro = new Object();
      	  segnalibro.pos = 'scheda';
		  segnalibro.counterHist=counter;            
		  segnalibro.idRecordHist=idRecord;            
      	  dhtmlHistory.add('scheda',segnalibro);
	}
	
	function historyChange(newLocation, historyData) {
		       			     
			// Usa anche i contatori nella history?         
			// 	counter=Number(historyData.counterHist);
			// 	idRecord=Number(historyData.idRecordHist);
			
		         if(newLocation=='scheda' && focusScheda==false){
		         	switch_vista();
		         }
		         else if(newLocation=='tab' && focusScheda==true){
		         	switch_vista();
		         }
	}
	
	
	
// ------------------------------ fine history	





	function openWindow(url, name, percent) {
	    var w = 630, h = 440; // default sizes
	    if (window.screen) {
	        w = window.screen.availWidth * percent / 100;
	        h = window.screen.availHeight * percent / 100;
	    }
	
	    window.open(url,name,'width='+w+',height='+h+' ,toolbar=yes, location=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
	}

	
	
	function apri_submask(id_table_parent,id_submask){
		
		
		inputs=document.getElementsByTagName('input');
		
			for(i=0;i<inputs.length;i++){
				if(inputs[i].id.substring(0,3)=="pk_"){
					var pk = inputs[i].value;
					var var_pk_id =inputs[i].id.substring(3);
				}
			}
		
			
		// se la chiave da passare � la PK
		if(var_pk_id==fkparent[id_submask]){
			
			openWindow('sottomaschera.php?oid_parent='+id_table_parent+'&id_submask='+id_submask+'&pk='+pk,'submask_'+id_submask,60);
		}
		else{
			campo_fk_sub=$('dati_' + fkparent[id_submask]);
			val_fk_sub=campo_fk_sub.value;
			openWindow('sottomaschera.php?oid_parent='+id_table_parent+'&id_submask='+id_submask+'&pk='+val_fk_sub,'submask_'+id_submask,60);
		}

		
	}
	
	
	
	Array.prototype.inArray = function (value)
	// Returns true if the passed value is found in the
	// array.  Returns false if it is not.
	{
	    var i;
	    for (i=0; i < this.length; i++) {
	        // Matches identical (===), not just similar (==).
	        if (this[i] === value) {
	            return true;
	        }
	    }
	    return false;
	};
	
	
function attiva_modifica_fck(editorInstance,nome_campo){
  	  editorInstance.Events.AttachEvent( 'OnSelectionChange', modfck ) ;
}

function blocca_fck(IDEditor){
	var editor = FCKeditorAPI.GetInstance(IDEditor);
	editor.ToolbarSet.Collapse() ;
	try{editor.EditorDocument.body.contentEditable = true;}
	catch(er){ }
	
	// il colore
	try{editor.EditorDocument.body.style.backgroundColor='#EEEEFF';}
	catch(er){ //alert(er);
	}
	
}

function attiva_fck(IDEditor){
	
	var editor = FCKeditorAPI.GetInstance(IDEditor);
	editor.ToolbarSet.Expand(); 
//	editor.EditorDocument.body.contentEditable = true;
	
	// il colore
	try{editor.EditorDocument.body.style.backgroundColor='#FFFFEE';}
//	try{editor.EditorDocument.body.className='modifica';}
	catch(er){ alert(er);}
	

}






function hotKeys (event) {
		

	  // Get details of the event dependent upon browser
	  event = (event) ? event : ((window.event) ? event : null);
	  
	  // We have found the event.
	  if (event) {   
	    
	    // Hotkeys require that either the control key or the alt key is being held down
	    if (event.ctrlKey || event.altKey || event.metaKey) {
	    
	      // Pick up the Unicode value of the character of the depressed key.
	      var charCode = (event.charCode) ? event.charCode : ((event.which) ? event.which : event.keyCode);
	      
	      // Convert Unicode character to its lowercase ASCII equivalent
	//      var myChar = String.fromCharCode (charCode).toLowerCase();
			var myChar = charCode;
	      
	      // Convert it back into uppercase if the shift key is being held down
//	      if (event.shiftKey) {myChar = myChar.toUpperCase();}
	          
	      // Now scan through the user-defined array to see if character has been defined.
	      for (var i = 0; i < keyActions.length; i++) {
	         
	        // See if the next array element contains the Hotkey character
	        if (keyActions[i].character == myChar 
	        	&& (
	        		(keyActions[i].mod=='ALT+SHIFT' && (event.altKey || event.metaKey) && event.shiftKey)
	        		||
	        		(keyActions[i].mod=='CTRL+SHIFT' && event.ctrlKey && event.shiftKey)
	        		||
	        		(keyActions[i].mod=='CTRL' && event.ctrlKey)
	        		||
	        		(keyActions[i].mod=='ALT' && (event.altKey || event.metaKey))
	        		||
	        		
	        		keyActions[i].mod==''
	        		)
        		){ 
	      
	          // Yes - pick up the action from the table
	          var action;
	            
	          // If the action is a hyperlink, create JavaScript instruction in an anonymous function
	          if (keyActions[i].actionType.toLowerCase() == "link") {
	            action = new Function ('location.href  ="' + keyActions[i].param + '"');
	          }
	            
	          // If the action is JavaScript, embed it in an anonymous function
	          else if (keyActions[i].actionType.toLowerCase()  == "code") {
	            action = new Function (keyActions[i].param);
	          }
	            
	          // Error - unrecognised action.
	          else {
	            alert (_('Hotkey Function Error: Action should be "link" or "code"'));
	            break;
	          }
	           
	          // At last perform the required action from within an anonymous function.
	          action ();
	         
	          // Hotkey actioned - exit from the for loop.
	          break;
	        }
	      }
	    }
	  }
} //-- fine funzione


 function get_json_tendina(id_reg){
 	
 	new Ajax.Request('./rpc.get_select_from.php', {
	  method: 'post',
	  postBody: 'id_reg='+id_reg,
	  onSuccess: function(transport) {
	  	t=transport.responseText;
	  	json1=$A(eval(transport.responseText));
	  	
	  	$('dati_'+json1[0].c).options.length=1;
		  		
	  	var ts =$('dati_'+json1[0].c).options;
		  		
		json1[0].val.each( function(v){ 
			ts[ts.length] = new Option(v[1], v[0]);
		} );
		
		nTendine++;
		
		$('feed_'+json1[0].c).update('');
		
		if(nTendine==tendineAttese){
			inizializza_scheda();
		}
	  }
 	});
 	
 }
 
 function _(str){
	
	return str;
	
}