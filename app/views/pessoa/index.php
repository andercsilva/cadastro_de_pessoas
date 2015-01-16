<h1>Cadastro de pessoas</h1>
<!-- Tabela responsável para listas as informações -->
<table>
  <thead>
    <tr>
    	<th colspan="4"><?php echo $title; ?></th>
    	<th class="adicionar"><a href="#" data-url="/pessoa/edit">Adicionar</a></th>
    </tr>
    <tr>
      <th>#</th>
      <th><?php echo $model->getLabel('nome') ?></th>
      <th><?php echo $model->getLabel('sobrenome') ?></th>
      <th colspan="2"><?php echo $model->getLabel('endereco') ?></th>
    </tr>
    <tr id="filter">
      <th><input class="width20" name="pessoa[id]" type="text"></input></th>
      <th><input name="pessoa[nome]" type="text"></input></th>
      <th><input name="pessoa[sobrenome]" type="text"></input></th>
      <th colspan="2"><input name="pessoa[endereco]" type="text"></input></th>
    </tr>    
  </thead>
  <tfoot>
    <tr>
      <td colspan="5" id="paginacao"><?php echo $dataprovider['paginate'] ?></td>
    </tr>
  </tfoot>  
  <tbody>
    <tr>
    <?php if(isset($dataprovider['data']) > 0) { ?>
    	<?php foreach($dataprovider['data'] as $data): ?>
        <tr>
          <td><?php echo $data->id; ?></td>
          <td><?php echo $data->nome; ?></td>
          <td><?php echo $data->sobrenome; ?></td>
          <td><?php echo $data->endereco; ?></td>
          <td>
            <a href="#" class="edit" data-url="/pessoa/edit/<?php echo $data->id ?>" class="button">Editar</a>
            <a href="#" class="delete" data-url="/pessoa/delete/<?php echo $data->id ?>" class="button">Excluir</a>
          </td>
        </tr>
  	  <?php endforeach; ?>
    <?php } else { ?>
        <tr>
          <td colspan="5">Nenhuma informação cadastrada</td>
        </tr>
    <?php } ?>
  </tbody>
</table>
<!-- Window responsável pelo cadastro -->
<div class="windowCadastro" style="display:none;width:100%; height:100%;">
  <form action="/pessoa/edit" method="post" class="basico" id="cadastro">
      <input id="id" name="pessoa[id]" value="" type="hidden">
      <h1 id="titulo">Cadastrar pessoa
          <span>Por favor preencha os campos abaixo.</span>
      </h1>
      <label>
          <span><?php echo $model->getLabel('nome') ?>:</span>
          <input id="nome" name="pessoa[nome]" placeholder="Digite seu nome" type="text">
      </label>
      
      <label>
          <span><?php echo $model->getLabel('sobrenome') ?>:</span>
          <input id="sobrenome" name="pessoa[sobrenome]" placeholder="Digite seu sobrenome" type="text">
      </label>
      
      <label>
          <span><?php echo $model->getLabel('endereco') ?>:</span>
          <textarea id="endereco" name="pessoa[endereco]" placeholder="Digite seu endereço"></textarea>
      </label>    
       <label>
          <span>&nbsp;</span> 
          <input class="button cadastrar" value="Cadastrar" type="submit"> 
          <input class="button cancelar" value="Cancelar" type="button"> 
      </label>
  </form>
</div>
