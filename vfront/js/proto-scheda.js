//javascript
var Scheda=new Array();

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



function date_encode(mydate){
	
	if(dateEncode=='iso') return mydate;
	
	var tk0=mydate.split(' ');
	
	var d0=tk0[0].split('-');
	
	if(d0.length!=3) return mydate;
	
	var ora0=(tk0.length==2) ? ' '+tk0[1] : '';
	
	switch(dateEncode){
		
		case 'ita':return d0[2]+"/"+d0[1]+"/"+d0[0]+ora0;
		break;
			
		case 'eng':return d0[1]+"/"+d0[2]+"/"+d0[0]+ora0;
		break;
	}
	
}



function date_decode(mydate){
	
	if(dateEncode=='iso') return mydate;
	
	var tk0=mydate.split(' ');
	
	var d0=tk0[0].split('/');
	
	if(d0.length!=3) return mydate;
	
	var ora0=(tk0.length==2) ? ' '+tk0[1] : '';
	
	switch(dateEncode){
		
		case 'ita':return d0[2]+"-"+d0[1]+"-"+d0[0]+ora0;
		break;
			
		case 'eng':return d0[2]+"-"+d0[0]+"-"+d0[1]+ora0;
		break;
	}
	
}

var http = createRequestObject();

var httpReload = createRequestObject();

var smoid_search=null;

function sndReq(action,offset,verifica_modifica) {
	
	// test se si è in fase di modifica
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
		if(counter+1 == max) return false;
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
	
	var url_string_rpc=pathRelativo+'/rpc.php?action='+action+'&c='+counter+'&id='+idRecord+'&hash=' + Math.random();
	url_string_rpc+='&'+window.location.search.substr(1);

	httpSnd = createRequestObject();
	httpSnd.open('GET', url_string_rpc);
// 	http.setRequestHeader("Content-Type", "text/plain; charset=utf-8");

    if(typeof(window['outputType']) == 'string' &&  window['outputType'] == 'JSON'){
        httpSnd.onreadystatechange = handleResponseJSON;
    }
    else{
        httpSnd.onreadystatechange = handleResponse;
    }
	httpSnd.send(null);
    
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


function richiediEMBED(sm_embed_id){
    
    httpEMBED = createRequestObject();
    var urlSUB= pathRelativo+'/sottomaschera.php?oid_parent='+jsOid+'&pk='+localIDRecord+'&id_submask='+sm_embed_id;
    httpEMBED.open('GET', urlSUB, true);
    httpEMBED.onreadystatechange = function (){
         if(httpEMBED.readyState == 4){
            if(httpEMBED.status == 200){

                var htmlEMBED= httpEMBED.responseText;
                $('sm_embed_'+sm_embed_id).innerHTML=htmlEMBED;
            }
        }
    };
    httpEMBED.send(null);
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
    
	$j('.table-submask-vis').hide();
	
	for(i=0;i<sottomaschere.length;i++){
	    	
		$('sm_'+sottomaschere[i]).style.fontWeight='normal';
		$('sm_'+sottomaschere[i]).value = sottomaschere_alias[i];
	}
}





function sndReqUpdate(action) {
		
    httpUp = createRequestObject();
    httpUp.open('POST',  pathRelativo+'/rpc.php?post=update&action='+action+'&c='+counter, true);
    httpUp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    //httpUp.setRequestHeader("Content-Type", "text/plain; charset=utf-8");
    httpUp.onreadystatechange = handleResponsePostUpdate;

    inputs = $$('input');
	
    var post_string='';
    
	for(j=0;j<inputs.length;j++){
		
		
		// PK
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
	
	
	// AGGIUNGI GLI EVENTUALI FCK
	if(fck_attivo){
			for(i=0;i<fck_vars.length;i++){
				
				//editorfck = FCKeditorAPI.GetInstance('dati_'+fck_vars[i]);
				editorfck = CKEDITOR.instances['dati_'+fck_vars[i]];

				// attribuisco all'hidden che si chiama come il fckeditor il valore assunto dallo stesso
				$('dati_'+fck_vars[i]).value=editorfck.getData();
			}
	}
	
	
	for(g=0;g<campi_mod.length;g++){
		
		valore_post=null;
		
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
				
				valore_post=$(campi_mod[g]).value;
			}
			
			// opzioni per le date
			else if($(campi_mod[g]).hasClassName('data')){
				valore_post=date_decode($F(campi_mod[g]));
			}
			else{
				valore_post=$(campi_mod[g]).value;
			}
			
			post_string += $(campi_mod[g]).name + "=";
			
			if (encodeURIComponent) {
			    post_string += encodeURIComponent(valore_post) + "&";
			} else {
				post_string += escape(valore_post) + "&";
			}
		}
	
    httpUp.send(post_string);
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
		
	httpNew = createRequestObject();
    httpNew.open('POST',  pathRelativo+'/rpc.php?post=new&action='+action, true);
    httpNew.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    httpNew.onreadystatechange = handleResponsePostNew;

    inputs = $$('input');
	
    var post_string='';
    
  
	for(j=0;j<inputs.length;j++){
		
		
		// PK
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
	
	// AGGIUNGI GLI EVENTUALI FCK
	if(fck_attivo){
			for(i=0;i<fck_vars.length;i++){
				
				//editorfck = FCKeditorAPI.GetInstance('dati_'+fck_vars[i]);
				editorfck = CKEDITOR.instances['dati_'+fck_vars[i]];
				
				// attribuisco all'hidden che si chiama come il fckeditor il valore assunto dallo stesso
				//$('dati_'+fck_vars[i]).value=editorfck.GetXHTML();
				$('dati_'+fck_vars[i]).value=editorfck.getData();
			}
	}

	

	
	for(g=0;g<campi_mod.length;g++){
		
		valore_post='';
		
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
				valore_post=$(campi_mod[g]).value;
			}
			else if($(campi_mod[g]).hasClassName('data')){
				
				valore_post=date_decode($F(campi_mod[g]));
			}
			else{
				
				valore_post=$(campi_mod[g]).value;
			}
		
			post_string += $(campi_mod[g]).name + "=";
			
			if (encodeURIComponent) {
			    post_string += encodeURIComponent(valore_post) + "&";
			} else {
				post_string += escape(valore_post) + "&";
			}
			
		}
		
    httpNew.send(post_string);
}




function sndReqPostCerca(action) {
    
	if(campi_mod.length>0){

	    var post_string='';
	    var fromsub='';
	    
		for(var g=0;g<campi_mod.length;g++){
		    
		    if(campi_mod[g]=='undefined'){
			
			continue;
		    }
		    
		    if(isSearchFromSub(campi_mod[g])){
			
			fromsub='&fromsub='+smoid_search;
		    }
			
		    if($j('#'+campi_mod[g]).attr('type')=='checkbox'){
			    valore_ricerca=$j('#'+campi_mod[g]+':checked') ? 1 : 0;
			    
		    }
		    else{
			
			    valore_ricerca=($j('#'+campi_mod[g]).hasClass('data')) 
				? date_decode($j('#'+campi_mod[g]).val()) : $j('#'+campi_mod[g]).val();
		    }

		    post_string += $j('#'+campi_mod[g]).attr('name') + "=";

		    if (encodeURIComponent) {
			post_string += encodeURIComponent(valore_ricerca) + "&";
		    } else {
			post_string += escape(valore_ricerca) + "&";
		    }
		    
		    
		}
		
		
		
		
	    httpSearch = createRequestObject();
	    httpSearch.open('POST', pathRelativo+'/rpc.php?post=cerca&action='+action+fromsub, true);
	    httpSearch.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	    httpSearch.onreadystatechange = handleResponsePostCerca;
	    httpSearch.send(post_string);
	}
	else{
		setStatus(_('No search was attempted! <br/> Enter values in at least one field'),3500,'risposta-arancio');
	}
}



function sndReqPostCercaFromGET(action,qs) {
		
	    http.open('POST', pathRelativo+'/rpc.php?post=cerca&action='+action, true);
	    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	    http.onreadystatechange = handleResponsePostCerca;
	
	    var post_string=qs;
		
	    http.send(post_string);
}





function sndReqPostDelete(action) {
		
	
    http.open('POST',  pathRelativo+'/rpc.php?post=delete&action='+action, true);
    http.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    

    inputs = $$('input');
	
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
    

    inputs = $$('input');
	
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
		
	inputs = $$('input');
	
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
    
	inputs = $$('input');
	
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



function sndReqGetone(field,id_record) {
    
    var oid=location.search.match(/oid=([0-9]+)/);
		
    httpGetone = createRequestObject();
    httpGetone.open('GET', pathRelativo+'/rpc.getone.php?oid='+oid[1]+'&id_record='+id_record+'&field='+field+'&hash=' + Math.random());
    httpGetone.onreadystatechange = handleResponseGetone;
    httpGetone.send(null);
}

function handleResponseGetone(){
	if(httpGetone.readyState == 4){
	 	var res = httpGetone.responseText
	 	
	 	var tk=res.split('|||');
		
		$j('#dati_ac_'+tk[0]).val(tk[1]);
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
	
	$('numeri').innerHTML=_('Record')+' <span id="goto" ondblclick="goto1();">'+(counter+1)+'</span> '+_('of')+' '+max;
	
}

function handleResponse() {
    if(httpSnd.readyState == 4){
        
    	
    	
    	var xml = httpSnd.responseXML;

    	
    // OPERAZIONI DI RESET	 ----------------------------------------------------------
    	
    	// Resetto i campi normali
    	document.forms.singleform.reset();
    	
    	
    	// Resetto gli evbentuali FCK editor
    	if(fck_attivo && (fck_vars.length>0)){
    		
    		for(var f=0;f<fck_vars.length;f++){

			CKEDITOR.instances["dati_"+fck_vars[f]].setData('',blocca_ck);
    			//FCKeditorAPI.GetInstance("dati_"+fck_vars[f]).SetHTML('');
    		}
    	}
    	
    //---------------------------------------------------------------------------------
    	
    	var tag0 = xml.getElementsByTagName("recordset").item(0);
    	var max = Number(tag0.attributes[0].value); // tot
    	
    	var tag1= xml.getElementsByTagName("row").item(0);
    	
    	if(Number(idRecord)>0){

    		// prendo l'attributo 'offset' del tag row
    		var takedCounter = tag1.attributes[0].value;
                counter = Number(takedCounter)-1;

                annulla();
    	}
    	
	// cancel all autocompleter_from
	$j('.autocomp_from_hidden').val('');
    	
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
		
		
			Scheda=new Array();
			
			for(i=0;i<n_nodi;i++){
			
				try{
					valore='';
					nome_nodo = nomi_nodi[i];
//					alert(nome_nodo);
					var puntatore = xml.getElementsByTagName(nome_nodo).item(0);
					valore=(puntatore.firstChild.data) ? puntatore.firstChild.data : '';
					
					Scheda[i]= new Array(nome_nodo,valore);
					
					
					// IMPOSTA LA CHIAVE PRIMARIA DEL RECORD
					if($("pk_"+nome_nodo)){
						
						$("pk_"+nome_nodo).value=valore;
						localIDRecord=valore;
					}
					
					// campi sola lettura
					if($("dati_"+nome_nodo).className=='onlyread-field'){
						
						$("dati_"+nome_nodo).innerHTML=valore+' ';
					}
					
					// Esclusione per i campi hidden
					else if($("dati_"+nome_nodo) && $("dati_"+nome_nodo).className!='nomodify') {
					
						// Attribuzione DATA o DATETIME
						if($("dati_"+nome_nodo).hasClassName('data')){

							$("dati_"+nome_nodo).value=date_encode(valore);
						}
						// Attribuzione GENERALE campo
						else{
							$("dati_"+nome_nodo).value=valore;
						}
						
						
						// CONDIZIONE PER I CHECKBOX
						if($("dati_"+nome_nodo).type=='checkbox'){
							
							if($("dati_"+nome_nodo).value=='1' || (PGdb==true && $("dati_"+nome_nodo).value=='t')){
								$("dati_"+nome_nodo).checked=true;
							}
							else{
								$("dati_"+nome_nodo).checked=false;
							}
							
						}

						//alert('nome:'+nome_nodo+'\nfck_attivo:' + fck_attivo + '\nfck_vars.inArray(nome_nodo):' + fck_vars.inArray(nome_nodo));

											
						if(fck_attivo && fck_vars.inArray(nome_nodo)){
							//FCKeditorAPI.GetInstance("dati_"+nome_nodo).SetHTML(valore);
							CKEDITOR.instances["dati_"+nome_nodo].setData(valore);
							//attiva_modifica_fck(FCKeditorAPI.GetInstance("dati_"+nome_nodo),nome_nodo);
						}
						
						
						
					}
					
				}
				catch(e){
//					xmlError(e);
				}
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
		
		// call the embedded subforms
                if(sm_embed.length>0){
                    
                    for(var l=0;l<sm_embed.length;l++){
                        
                        richiediEMBED(sm_embed[l]);
                    }
                }
	
		
		for(var a=0;a<campiAutocompleterFrom.length;a++){
		    
		    if(campiAutocompleterFrom[a]!=''){
			tmp_var1=campiAutocompleterFrom[a].substr(5);
			tmp_val1=$(campiAutocompleterFrom[a]).value;
		    }
		    
		    sndReqGetone(tmp_var1, tmp_val1);
		}
		
			
		stato_p(counter,max);
		
		$('refresh').innerHTML='';	
		
    }
    else {
    	$('refresh').innerHTML=' <img src="./img/refresh1.gif" width="12" heigth="12" alt="caricamento..." /> ' + _('Updating ...');
    }
}


function handleResponseJSON() {
    if(httpSnd.readyState == 4){
        
        var json = JSON.parse(httpSnd.responseText);
        
        // OPERAZIONI DI RESET	 ----------------------------------------------------------
        // 
    	// Resetto i campi normali
    	document.forms.singleform.reset();
    	
    	// Resetto gli evbentuali FCK editor
    	if(fck_attivo && (fck_vars.length>0)){
    		
    		for(var f=0;f<fck_vars.length;f++){

			CKEDITOR.instances["dati_"+fck_vars[f]].setData('',blocca_ck);
    			//FCKeditorAPI.GetInstance("dati_"+fck_vars[f]).SetHTML('');
    		}
    	}
        //---------------------------------------------------------------------------------
    	
    	var max = Number(json.tot); // tot
    	
    	if(Number(idRecord)>0){

    		// prendo l'attributo 'offset' del tag row
    		var takedCounter = json.row[0].offset;
                counter = Number(takedCounter)-1;

                annulla();
    	}
        
        // cancel all autocompleter_from
        jQuery('.autocomp_from_hidden').val('');
    	
    	var n_nodi=0;
    	var nomi_nodi=new Array();
		
        for (i in json.row[0].data) {
            nomi_nodi[n_nodi++] = i;
        }
		
        Scheda=new Array();

        for(i=0;i<n_nodi;i++){

            try{
                valore='';
                nome_nodo = nomi_nodi[i];
                valore= json.row[0].data[nome_nodo];
                Scheda[i]= new Array(nome_nodo,valore);

                // IMPOSTA LA CHIAVE PRIMARIA DEL RECORD
                if($("pk_"+nome_nodo)){

                    $("pk_"+nome_nodo).value=valore;
                    localIDRecord=valore;
                }

                // campi sola lettura
                if($("dati_"+nome_nodo).className=='onlyread-field'){

                    $("dati_"+nome_nodo).innerHTML=valore+' ';
                }

                // Esclusione per i campi hidden
                else if($("dati_"+nome_nodo) && $("dati_"+nome_nodo).className!='nomodify') {

                    // Attribuzione DATA o DATETIME
                    if($("dati_"+nome_nodo).hasClassName('data')){

                        $("dati_"+nome_nodo).value=date_encode(valore);
                    }
                    // Attribuzione GENERALE campo
                    else{
                        $("dati_"+nome_nodo).value=valore;
                    }


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
                        CKEDITOR.instances["dati_"+nome_nodo].setData(valore);
                    }
                }
            }
            catch(e){
                // xmlError(e);
            }
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
		
		// call the embedded subforms
                if(sm_embed.length>0){
                    for(var l=0;l<sm_embed.length;l++){
                        richiediEMBED(sm_embed[l]);
                    }
                }
	
		
		for(var a=0;a<campiAutocompleterFrom.length;a++){
		    if(campiAutocompleterFrom[a]!=''){
                tmp_var1=campiAutocompleterFrom[a].substr(5);
                tmp_val1=$(campiAutocompleterFrom[a]).value;
		    }
		    sndReqGetone(tmp_var1, tmp_val1);
		}
			
		stato_p(counter,max);
		
		$('refresh').innerHTML='';	
		
    }
    else {
    	$('refresh').innerHTML=' <img src="./img/refresh1.gif" width="12" heigth="12" alt="caricamento..." /> ' + _('Updating ...');
    }
}



function handleResponsePostUpdate(){
	 if(httpUp.readyState == 4){

	 	var res = $j.parseJSON(httpUp.responseText);

	 	if(res.error==false){

		    if(res.aff_rows>0){
			setStatus(_('Record updated correctly'),3500,'risposta-giallo');
			campi_mod = new Array();
		    }
		    else{
			setStatus(_('No changes in update'),3500,'risposta-arancio');
		    }
		}
		else{
			setStatus( _('Error updating record') + ': ' + res.error_code.code+' '+res.error_code.msg ,6000,'risposta-arancio');
		}
		
		$('refresh').innerHTML='';
	 }
}

function handleResponsePostNew(){

	 if(httpNew.readyState == 4){

	 	var res = $j.parseJSON(httpNew.responseText);
	 	
	 	if(res.error!=false){
			
			setStatus(_('Unable to insert record')+': '+res.error_code.code+' '+res.error_code.msg ,6000,'risposta-arancio');
		}
	 	else{
	 		
	 		// cambia il valore di max
	 		max=max+1;
	 		// aggiorna i contatori ed i pulsanti
	 		stato_p();
	 		
	 		// imposta l'idRecord
	 		idRecord = res.id;
	 		
	 		
	 		sndReq(tabella,'id',false);
	 		
	 		// se � una scheda "nuovo valore"
	 		if(haveParent){
	 			
	 			// Manda la richiesta di aggiornamento della tendina...
			    hash_aggiornato=false;
			    httpReload.open('POST',  pathRelativo+'/rpc.refresh_iframe.php');
			    httpReload.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
			    httpReload.onreadystatechange = handleReqRicaricaTendina;
			    httpReload.send('tabella='+parentTable+'&campo='+parentField);

                            window.opener.document.getElementById('i_id_'+parentField).className='on';
                            window.opener.document.getElementById('i_id_'+parentField).disabled='';

                            // se � in modalit� modifica nel parent proponi l'ultimo valore

	 			
	 		}
	 		
			setStatus(_('Record inserted correctly'),3500,'risposta-giallo');
			campi_mod = new Array();
		}
		
	 }
}



function handleReqRicaricaTendina(){
	
	
	if(httpReload.readyState == 4){
		
	 	hash_aggiornato = httpReload.responseText;
	 	
	 	window.opener.document.getElementById('i_id_'+parentField).src='files/html/'+hash_aggiornato+'.html';
	 	
                window.opener.document.getElementById('i_id_'+parentField).className='on';
                window.opener.document.getElementById('i_id_'+parentField).disabled='';

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
	
 	if(httpSearch.readyState == 4){
	 	var risposta_sql = httpSearch.responseText;

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
			
			var qresults = new Ajax.Request( pathRelativo+"/rpc.export_search.php", 
					{method: 'post', 
					parameters: 'table='+tabella+'&qresults='+risposta_sql, asynchronous: true
					});
			
		    setStatus(nRisultati+' '+_('records found for this search'),3500000,'risposta-verdino');
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

function CKeditor_OnComplete(){


	// Temporary workaround for providing editor 'read-only' toggling functionality.
    ( function()
		{
		   var cancelEvent = function( evt )
			  {
				 evt.cancel();
			  };

		   CKEDITOR.editor.prototype.readOnly = function( isReadOnly )
		   {
			  // Turn off contentEditable.
			  this.document.$.body.disabled = isReadOnly;
			  CKEDITOR.env.ie ? this.document.$.body.contentEditable = !isReadOnly
			  : this.document.$.designMode = isReadOnly ? "off" : "on";

			  // Prevent key handling.
			  this[ isReadOnly ? 'on' : 'removeListener' ]( 'key', cancelEvent, null, null, 1 );
			  this[ isReadOnly ? 'on' : 'removeListener' ]( 'selectionChange', cancelEvent, null, null, 1 );

			  // Disable all commands in wysiwyg mode.
			  var command,
				 commands = this._.commands,
				 mode = this.mode;

			  for ( var name in commands )
			  {
				 command = commands[ name ];
				 isReadOnly ? command.disable() : command[ command.modes[ mode ] ? 'enable' : 'disable' ]();
				 this[ isReadOnly ? 'on' : 'removeListener' ]( 'state', cancelEvent, null, null, 0 );
			  }
		   }
		} )();


    fck_pronti=fck_vars.length;

	
	
	for(i=0;i<fck_vars.length;i++){
		
		CKEDITOR.instances['dati_'+fck_vars[i]].on('key', function(ee) { 
			if(modificaRecord||nuovoRecord||ricerca){
				mod(ee.sender.name);
			}
			else{
				return false;
			}
		});
	}

	blocca_ck();

    if(fck_pronti==fck_vars.length && (tendineAttese-nTendine)==0 ){

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
			$('numeri').innerHTML=_('There are no records in this table');
			
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
		
		// manda l'eventuale ricerca di GET
		if(GETqs!=''){
			sndReqPostCercaFromGET(tabella,GETqs);
		}
		

}







function attiva_campi(classe){
	
	if(classe=='ricerca'){
		cn='s';
	}
	else {
		cn='on';
	}
	
	inputs = $$('input');
	textareas = $$('textarea');
	selects = $$('select');
	
	for(j=0;j<inputs.length;j++){
	    
		if(cn=='s' && inputs[j].hasClassName('hh_field')){
		    
		    inputs[j].readOnly=false;
		    chfield(inputs[j],cn);
		    
		}
		else if(inputs[j].id.substring(0,5)=="dati_" 
		  && inputs[j].type!='hidden' 
		  && inputs[j].type!='checkbox'
		  && !inputs[j].hasClassName('hh_field')) {
		  
			inputs[j].readOnly=false;
			chfield(inputs[j],cn);
			
		}
		else if(inputs[j].id.substring(0,5)=="dati_" && inputs[j].type=='checkbox'){
			inputs[j].disabled=false;
			chfield(inputs[j],cn);
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
			chfield(textareas[j],cn);
		}
	}
	
	for(j=0;j<selects.length;j++){
		if(selects[j].id.substring(0,5)=="dati_"){
			selects[j].disabled=false;
			chfield(selects[j],cn);
		}
	}
	
	// FCK
	if(fck_attivo){

		if(classe=='ricerca'){
			for(i=0;i<fck_vars.length;i++){
					//FCKeditorAPI.GetInstance("dati_"+fck_vars[i]).SetHTML('');
					CKEDITOR.instances["dati_"+fck_vars[i]].setData('',attiva_ck);
			}
		}
		attiva_ck();
	}
	
	
	$('p_update').disabled=true;
	$('p_annulla').disabled=false;
	
	
}

function disattiva_campi(){

	inputs=$$('input');
	textareas = $$('textarea');
	selects = $$('select');
	
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
				if(inputs[j].hasClassName('data')){
					inputs[j].className='off data';
				}
				else {
					chfield(inputs[j],'off');
				}
					
			}
			
		}
	}
	
	
	for(j=0;j<textareas.length;j++){
		if(textareas[j].id.substring(0,5)=="dati_"){
			textareas[j].readOnly=true;
			chfield(textareas[j],'off');
		}
	}
	
	
	for(j=0;j<selects.length;j++){
		if(selects[j].id.substring(0,5)=="dati_"){
			selects[j].disabled=true;
			chfield(selects[j],'off');
		}
	}

	// FCK
	if(fck_attivo){
		blocca_ck();
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
		
		$$('.pulsante-submask').each(function (e){e.enable();});
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
	
	// search on autocompleter_from
	if(id.substr(0,8)=='dati_ac_'){
	    id='dati_'+id.substr(8);
	}
	
	
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
			
			//FCKeditorAPI.GetInstance('dati_'+fck_vars[i]).EditorDocument.body.innerHTML='';
			CKEDITOR.instances["dati_"+fck_vars[i]].setData('',attiva_ck);
		}
	}

	// READONLY fields
	$j('.hh_field').each( function (){$j(this).val('');});
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
	
	inputs = $$('input');
	textareas = $$('textarea');
	
	// eventually embedded subforms
	$j('.embed-nodata').hide();
	$j('.sub-search').show();
	

	
	$j('.table-submask-vis').each( function(){
	   
	   table_on_search($j(this));
	});
	
	
	
	f=document.forms.singleform;
	f.reset();
	
	attiva_campi('ricerca');
	
	// Metti una variabile 'new' su localIDRecord utile per allegati e link
	localIDRecord = 'ric';
	richiediAL();
	
	ricerca =true;
	
	$('p_cerca').value=_('Start search');
	$('p_cerca').className='var';
	
	$$('.pulsante-submask').each(function (e){e.disable();});
}

function table_on_search(obj){
    
    var trs=$j(obj).find('tr');
    
    for(var i=2;i<trs.length;i++){
	$j(trs[i]).remove();
    }
    
    $j(trs[1]).find('input,checkbox,select,textarea').each( function(){$j(this).val('');});
}


function isSearchFromSub(){
    
    smoid_search=null;
    
    if(campi_mod.length==0){
	
	return false;
    }
    else{
	
	var pattern=/^dati__[0-9]+__.*/g;
	
	for(g=0;g<campi_mod.length;g++){
	    
	    if(campi_mod[g].match(pattern)){
		
		smoid_search=$j("#"+campi_mod[g]).parents().find('table.table-submask').attr('id').substr(7);
		return true;
	    }
	}
	
	return false;
    }
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
		
		if($(nome_campo).className=='onlyread-field'){
			// skip
		}
		else if($(nome_campo).value=='' || $(nome_campo).value==null){
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
		
		//debugVar+="\ncampiSearch: Array "+campiSearch.join("|");
		
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
		else if(n==23505){
			return _('Can\'t add record - Duplicate key');
		}	
		else if(n==23503){
			return _('Unable to add a record - The reference is missing and/or not connected to the reference table.');
		}	
		else if(n==1345){
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
		else if(n==1022){
			return _('Can\'t add record - Duplicate key');
		}	
		else if(n==1452){
			return _('Unable to add a record - The reference is missing and/or not connected to the reference table.');
		}	
		else if(n==1395){
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
	var keynum;
	var keychar;
	var numcheck;
	
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

	//alert(keynum);
}


function switch_vista(){
		
		if(focusScheda){
	
			// Switch:
			$('scheda1').style.display='none';
			$('scheda-tabella').style.display='';
		
			$('p_prev').style.display='none';
			$('p_next').style.display='none';

			if($('popup-hotkeys') != undefined){
				$('popup-hotkeys').style.display='none';
			}
			
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

			$('popup-hotkeys').style.display='';
			
			focusScheda=true;
			
			if(usaHistory){
				
				var segnalibro = new Object();
	      		segnalibro.pos = "scheda";
				segnalibro.counterHist=counter;            
				segnalibro.idRecordHist=idRecord;            
	      		dhtmlHistory.add("scheda",segnalibro);
			}
			
			if(ricerca){
				
				$('p_annulla').enable();
			}
	}
		
}



// FUNZIONI DI HISTORY

    
	function history_initialize() {
	
		window.dhtmlHistory.create({
			toJSON: function(o) {
				return Object.toJSON(o);
			}, fromJSON: function(s) {
				return s.evalJSON();
			}
		});
		
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

	
	
	function apri_submask(id_table_parent,id_submask, on_shadowbox){
		
		inputs=$$('input');
		
			for(i=0;i<inputs.length;i++){
				if(inputs[i].id.substring(0,3)=="pk_"){
					var pk = inputs[i].value;
					var var_pk_id =inputs[i].id.substring(3);
				}
			}
			
		// se la chiave da passare � la PK
		if(var_pk_id==fkparent[id_submask]){
			
                        var link_submask='sottomaschera.php?oid_parent='+id_table_parent+'&id_submask='+id_submask+'&pk='+pk;
                        
                        if(on_shadowbox==true){
                            Shadowbox.open({content: link_submask, player: 'iframe', width: '780'});
                        }
                        else{ 
                            openWindow(link_submask,'submask_'+id_submask,60);
                        }
		}
		else{
			campo_fk_sub=$('dati_' + fkparent[id_submask]);
			val_fk_sub=campo_fk_sub.value;
                        
                        var link_submask='sottomaschera.php?oid_parent='+id_table_parent+'&id_submask='+id_submask+'&pk='+val_fk_sub;
                        
                        if(on_shadowbox==true){
                            Shadowbox.open({content: link_submask, player: 'iframe', width: '780'});
                        }
                        else{
                            openWindow(link_submask,'submask_'+id_submask,60);
                        }
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
  	  //editorInstance.Events.AttachEvent( 'OnSelectionChange', modfck ) ;
}

function blocca_ck(){


	for(i=0;i<fck_vars.length;i++){

		CKEDITOR.instances['dati_'+fck_vars[i]].readOnly(true);
		CKEDITOR.instances['dati_'+fck_vars[i]].document.$.body.style.backgroundColor='#EEEEFF';

	}
}


function attiva_ck(){

	for(i=0;i<fck_vars.length;i++){
		CKEDITOR.instances['dati_'+fck_vars[i]].readOnly(false);
		if(ricerca){
			var color_ck='#EEFFEE';
		}
		else {
			var color_ck='#FFFFEE';
		}
		CKEDITOR.instances['dati_'+fck_vars[i]].document.$.body.style.backgroundColor=color_ck;
	}
}

function hotKeys(event) {
		

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
	        		(keyActions[i].mod=='' &&  event.metaKey)
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



function chfield(obj,cl){
	
	if(obj.hasClassName('off')) obj.removeClassName('off');
	if(obj.hasClassName('on')) obj.removeClassName('on');
	if(obj.hasClassName('s')) obj.removeClassName('s');
	
	obj.addClassName(cl);
}


function get_autocompleter_from_id(text, li){
    
	//alert (li.id);
	
	// [1]=filed name
	// [2]=val
	var tkk=li.id.split('___');
	
	$('dati_'+tkk[1]).value=tkk[2];
}


function get_scheda_val(campo){
	
	var parsed_campo = campo.split(':');
	
	for(i=0;i<Scheda.length;i++){
		if(undefined!=Scheda[i] && parsed_campo[0]==Scheda[i][0]){
			
			// options: nome_campo, nome_campo:value
			if(parsed_campo[1]=='label'){
				
				v = $('dati_'+parsed_campo[0]).options[$('dati_'+parsed_campo[0]).selectedIndex].text;
		
				return (undefined!=v) ? v:'';
			}
			else{
				return Scheda[i][1];
			}
		}
	}
	
	// fake else
	return '';
}


function entry_table_search(){
	
	switch_vista();
	table_search_mode(1);
}


function table_search_mode(mode){
	
	if(mode==0){
		
		$('buttons_on_research').hide();
		$('pulsanti').show();
		$('counter_container').show();
	}
	else{
		$('buttons_on_research').show();
		$('pulsanti').hide();
		$('counter_container').hide();
	}
}


function exit_table_search(){
	
	table_search_mode(0);
	switch_vista();
	setStatus('');
	annulla();
}


// -------------------------------------------------------



function check_perm(){
	
	  httpPerm = createRequestObject();
	  httpPerm.open('GET', pathRelativo+'/rpc.perm.php?action='+ tabella_alias +'&id='+localIDRecord+'&hash=' + Math.random(),true);
	  httpPerm.onreadystatechange = handleResponsePerm;
	  httpPerm.send(null);
}


function handleResponsePerm(){
	
	
	if(httpPerm.readyState == 4){
		if(httpPerm.status == 200){
	        
	    	var Perm = httpPerm.responseText;
	    	
	    	
		    $('refresh').innerHTML='';
		}
		else{
			
			alert(_('Error Loading RPC request. Status:')+httpAL.status);
		}
	}
}

/*
function isset(varname){
    
    if(typeof(window.varname) != "undefined" || typeof(window[varname]) != "undefined") return true;
    else return false;
}
*/


document.observe("dom:loaded", function() {

	

	if(fck_attivo){

		var cccc=0;

		CKEDITOR.on( 'instanceReady', function( ev ){
			cccc++;
			if(cccc==fck_vars.length){
				CKeditor_OnComplete();
			}
		});
	}

        if(window.location.hash=='#tab'){
            switch_vista();
        }

	$j(document).ready( function(){

	   $j('[name^="dati["]').bind('change keypress keyup',
		function (){
		    if(!$j(this).attr('readonly'))
			mod($j(this).attr('id'));
		    
	    });

	    // get fiters

	    $j('.cancel_filter').live('click', function(){

		var filter_to_canc=$j(this).attr('rel');
		new_url=$j.query.REMOVE('w['+filter_to_canc+']');
		window.location.search=new_url;
	    });

	    $j('.cancel_all_filter').live('click', function(){

		new_url=$j.query.REMOVE('w');
		window.location.search=new_url;
	    });

	});
});


