/**
* Arquivo pessoa.js onde é definido toda a movimentação de insert, update, delete
* @package Cadastro de Pessoas
* @category pessoa
* @name index
* @author Anderson Silva <anderson@haarieh.com>
* @copyright Haarieh Ltda ME
* @since 01-12-2015
**/
//Popular a tabela no formato ajax sem precisar de reload da página
$.populateTable = function(pessoas) {
	//Seleciona a tabela 
	var table = $("table tbody");
	//Define a variável item
	var item = '';
	//Loop no objeto
	$.each(pessoas, function(index, pessoa) { 		 		
 		item = item + "<tr><td>" + pessoa.id + "</td>";
 		item = item + "<td>" + pessoa.nome + "</td>";
 		item = item + "<td>" + pessoa.sobrenome + "</td>";
 		item = item + "<td>" + pessoa.endereco + "</td>";
 		item = item + '<td><a href="#" class="edit" title="Editar registro"  data-url="/pessoa/edit/'+pessoa.id+'" class="button"><img src="/images/editar.png"/></a><a href="#" class="delete" title="Excluir registro" data-url="/pessoa/delete/'+pessoa.id+'" class="button"><img src="/images/excluir.png"/></a></td></tr>';
 		table.html(item);
	}); 

}

//Atualiza a tabela
$.updateTable = function(url) {
	var dados = $('#filter :input').serialize();
	jQuery.ajax({
		url : url,
		data: dados,
		type : 'POST',
		beforeSend : function() { 
			$('table').showLoading({'addClass': 'loading-indicator-bars'});
		},
		success : function(data) {
			$('table').hideLoading();
			if(data) {				
				var obj = jQuery.parseJSON(data);			
				$.populateTable(obj.data);
				$("#paginacao").html(obj.paginate);
				$.pagina = obj.pagina;
			} else {
				if($.pagina > 1) {
					$.pagina--;
					$.updateTable('/pessoa/search/' + $.pagina);
				} else {
					$("table tbody").html('<tr><td colspan="5">Nenhuma informação cadastrada</td></tr>')
					$("#paginacao").html('');
				}
			}
			
		},
		error : function(a,b,c){ 
			$('#content').hideLoading();
			$.showAlert(a.responseText, 'error');
		},		
		'cache':false		
	});		
}

//Detecta o enter no filter e atualiza com o filtro
$( "#filter input" ).on( "keydown", function(event) {
  if(event.which == 13) 
     $.updateTable('/pessoa/search/' + $.pagina);
});

//Adicionar um Cadastro
$(document).on('click', '.adicionar', function(e) {
	//Altera o formulario
	$('#cadastro').attr('action','/pessoa/edit/');
	$('.cadastrar').val('Cadastrar');
	$("#titulo").html('Cadastrar pessoa<span>Por favor preencha os campos abaixo.</span>');
	//Limpa o ID
	$('#id').val('');
	//Limpa o formulário
	$('#cadastro').clearForm();
	//Mostra a janela do cadastro
	$('.windowCadastro').show();
	$('.windowCadastro').center();
	return false;
});

//Altera um Cadastro
$(document).on('click', 'a.edit', function(e) {
	var url = $(this).attr('data-url');
	jQuery.ajax({
		url : url,
		type : 'GET',
		beforeSend : function() { 
			$('table').showLoading({'addClass': 'loading-indicator-bars'});
		},
		success : function(data) {
			$('table').hideLoading();
			//Transforma em objeto
			var obj = jQuery.parseJSON(data);
			//Seta a pessoa
			var pessoa = obj.data;
			//Seta os valores nos inputs
			$('#id').val(pessoa.id);
			$('#nome').val(pessoa.nome);
			$('#sobrenome').val(pessoa.sobrenome);
			$('#endereco').val(pessoa.endereco)
			//Altera o action
			$('#cadastro').attr('action','/pessoa/edit/' + pessoa.id);
			//Altera o formulário
			$('.cadastrar').val('Alterar');
			$("#titulo").html('Alterar o cadastro<span>Por favor preencha os campos abaixo.</span>');
			//Mostra a janela e centraliza
			$('.windowCadastro').show();
			$('.windowCadastro').center();
		},
		error : function(a,b,c){ 
			$('table').hideLoading();
			$.showAlert(a.responseText, 'error');
		},		
		'cache':false		
	});	
	return false;	
});

//Deleta a informação
$(document).on('click', 'a.delete', function(e) {
	var url = $(this).attr('data-url');
	jQuery.ajax({
		url : url,
		type : 'GET',
		beforeSend : function() { 
			$('table').showLoading({'addClass': 'loading-indicator-bars'});
		},
		success : function(data) {
			$('table').hideLoading();
			$.showAlert(data, 'success');
			$.updateTable('/pessoa/search/' + $.pagina);
		},
		error : function(a,b,c){ 
			$('table').hideLoading();
			$.showAlert(a.responseText, 'error');
		},		
		'cache':false		
	});	
	return false;	
});

//Envia a informação do cadastro ou alteração
$(document).on('submit', '#cadastro', function(e) {
	var acao = $(this).attr('action');
	var dados = $( this ).serialize();
	jQuery.ajax({
		url : acao,
		type : 'POST',
		data: dados,
		beforeSend : function() { 
			$('table').showLoading({'addClass': 'loading-indicator-bars'});
		},
		success : function(data) {
			$('table').hideLoading();
			$('.windowCadastro').hide();
			var obj = jQuery.parseJSON(data);
			$.showAlert(obj.mensagem, 'success');
			$.updateTable('/pessoa/search/' + $.pagina);
		},
		error : function(a,b,c){ 
			$('table').hideLoading();
			$.showAlert(a.responseText, 'error');
		},		
		'cache':false		
	});	
	return false;	
});

$(document).on('click', '.cancelar', function(e) {
	$('.windowCadastro').hide();
	return false;
});

//Ao clicar na paginação
$(document).on('click', '.page', function(e) {
	var url = $(this).attr('data-url');
	$.updateTable(url);
	return false;
});
//Define a página ao carregar o JavaScript
$.pagina = 1;