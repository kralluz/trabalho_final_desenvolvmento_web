<?php

//<fileHeader>

//</fileHeader>

class TriagemForm extends TPage
{
    protected BootstrapFormBuilder $form;
    private $formFields = [];
    private static $database = 'horus';
    private static $activeRecord = 'Triagem';
    private static $primaryKey = 'id';
    private static $formName = 'form_TriagemForm';

    //<classProperties>

    //</classProperties>

    use Adianti\Base\AdiantiFileSaveTrait;
    use BuilderMasterDetailFieldListTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Triagem de Paciente");

        $criteria_carater_id = new TCriteria();
        $criteria_triagem_procedimento_triagem_procedimento_id = new TCriteria();
        $criteria_classificacao_id = new TCriteria();
        $criteria_atendimento_servico_id = new TCriteria();
        $criteria_encaminha_id = new TCriteria();

        $filterVar = TSession::getValue("userunitid");
        $criteria_encaminha_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_unidade WHERE system_unit_id = '{$filterVar}')")); 
        $filterVar = Papel::FUNCIONARIO;
        $criteria_encaminha_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_papel WHERE papel_id = '{$filterVar}')")); 

        //<onBeginPageCreation>

        //</onBeginPageCreation>

        $cabecalho_triagem = new BPageContainer();
        $queixa = new TText('queixa');
        $carater_id = new TDBCombo('carater_id', 'horus', 'CaraterAtendimento', 'id', '{codigo} - {nome}','codigo asc' , $criteria_carater_id );
        $peso = new TNumeric('peso', '2', ',', '.' );
        $altura = new TNumeric('altura', '0', ',', '.' );
        $imc = new TNumeric('imc', '2', ',', '.' );
        $imc_classificacao = new TEntry('imc_classificacao');
        $temperatura = new TNumeric('temperatura', '1', ',', '.' );
        $perimetro_encefalico = new TNumeric('perimetro_encefalico', '2', ',', '.' );
        $hgt_jejum = new TRadioGroup('hgt_jejum');
        $hgt_hi = new TRadioGroup('hgt_hi');
        $hgt_lo = new TRadioGroup('hgt_lo');
        $hgt = new TNumeric('hgt', '0', ',', '.' );
        $dor = new TRadioGroup('dor');
        $pa_sistolica = new TNumeric('pa_sistolica', '0', ',', '.' );
        $pa_distolica = new TNumeric('pa_distolica', '0', ',', '.' );
        $saturacao = new TNumeric('saturacao', '0', ',', '.' );
        $fc = new TNumeric('fc', '0', ',', '.' );
        $fr = new TNumeric('fr', '0', ',', '.' );
        $comorbidade = new TRadioGroup('comorbidade');
        $comorbidade_obs = new TEntry('comorbidade_obs');
        $alergia = new TRadioGroup('alergia');
        $alergia_obs = new TEntry('alergia_obs');
        $triagem_procedimento_triagem_id = new THidden('triagem_procedimento_triagem_id[]');
        $triagem_procedimento_triagem___row__id = new THidden('triagem_procedimento_triagem___row__id[]');
        $triagem_procedimento_triagem___row__data = new THidden('triagem_procedimento_triagem___row__data[]');
        $triagem_procedimento_triagem_procedimento_id = new TDBUniqueSearch('triagem_procedimento_triagem_procedimento_id[]', 'horus', 'Sigtap', 'id', 'nome','nome asc' , $criteria_triagem_procedimento_triagem_procedimento_id );
        $this->list_procedimentos = new TFieldList();
        $obs = new TText('obs');
        $triagem_anexo_triagem_id = new THidden('triagem_anexo_triagem_id[]');
        $triagem_anexo_triagem___row__id = new THidden('triagem_anexo_triagem___row__id[]');
        $triagem_anexo_triagem___row__data = new THidden('triagem_anexo_triagem___row__data[]');
        $triagem_anexo_triagem_path = new TFile('triagem_anexo_triagem_path[]');
        $triagem_anexo_triagem_descricao = new TEntry('triagem_anexo_triagem_descricao[]');
        $this->list_anexos = new TFieldList();
        $classificacao_id = new TDBRadioGroup('classificacao_id', 'horus', 'Classificacao', 'id', '{nome}','id asc' , $criteria_classificacao_id );
        $id = new THidden('id');
        $pessoa_id = new THidden('pessoa_id');
        $dt_triagem = new THidden('dt_triagem');
        $situacao_id = new THidden('situacao_id');
        $atendimento_id = new THidden('atendimento_id');
        $profissional_id = new THidden('profissional_id');
        $cbo_id = new THidden('cbo_id');
        $atendimento_servico_id = new TDBCheckGroup('atendimento_servico_id', 'horus', 'AtendimentoServico', 'id', '{nome}','nome asc' , $criteria_atendimento_servico_id );
        $desfecho = new TRadioGroup('desfecho');
        $local_id = new THidden('local_id');
        $encaminha_id = new TDBUniqueSearch('encaminha_id', 'horus', 'Pessoa', 'id', 'nome','nome asc' , $criteria_encaminha_id );
        $desfecho_obs = new TText('desfecho_obs');

        $this->list_procedimentos->addField(null, $triagem_procedimento_triagem_id, []);
        $this->list_procedimentos->addField(null, $triagem_procedimento_triagem___row__id, ['uniqid' => true]);
        $this->list_procedimentos->addField(null, $triagem_procedimento_triagem___row__data, []);
        $this->list_procedimentos->addField(new TLabel("Procedimento", null, '14px', null), $triagem_procedimento_triagem_procedimento_id, ['width' => '100%']);

        $this->list_procedimentos->width = '100%';
        $this->list_procedimentos->setFieldPrefix('triagem_procedimento_triagem');
        $this->list_procedimentos->name = 'list_procedimentos';

        $this->criteria_list_procedimentos = new TCriteria();
        $this->default_item_list_procedimentos = new stdClass();

        $this->form->addField($triagem_procedimento_triagem_id);
        $this->form->addField($triagem_procedimento_triagem___row__id);
        $this->form->addField($triagem_procedimento_triagem___row__data);
        $this->form->addField($triagem_procedimento_triagem_procedimento_id);

        $this->list_procedimentos->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $this->list_anexos->addField(null, $triagem_anexo_triagem_id, []);
        $this->list_anexos->addField(null, $triagem_anexo_triagem___row__id, ['uniqid' => true]);
        $this->list_anexos->addField(null, $triagem_anexo_triagem___row__data, []);
        $this->list_anexos->addField(new TLabel("Arquivo (.DPF; .JPEG; .JPG; .PNG)", null, '14px', null), $triagem_anexo_triagem_path, ['width' => '50%']);
        $this->list_anexos->addField(new TLabel("Descrição", null, '14px', null), $triagem_anexo_triagem_descricao, ['width' => '50%']);

        $this->list_anexos->width = '100%';
        $this->list_anexos->setFieldPrefix('triagem_anexo_triagem');
        $this->list_anexos->name = 'list_anexos';

        $this->criteria_list_anexos = new TCriteria();
        $this->default_item_list_anexos = new stdClass();

        $this->form->addField($triagem_anexo_triagem_id);
        $this->form->addField($triagem_anexo_triagem___row__id);
        $this->form->addField($triagem_anexo_triagem___row__data);
        $this->form->addField($triagem_anexo_triagem_path);
        $this->form->addField($triagem_anexo_triagem_descricao);

        $this->list_anexos->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $hgt_hi->setChangeAction(new TAction([$this,'onGlicemia']));
        $hgt_lo->setChangeAction(new TAction([$this,'onHGT']));
        $comorbidade->setChangeAction(new TAction([$this,'onHabilitaComorbidade']));
        $alergia->setChangeAction(new TAction([$this,'onHabilitaAlergia']));

        $altura->setExitAction(new TAction([$this,'onCalcIMC']));
        $imc->setExitAction(new TAction([$this,'onReload']));

        $classificacao_id->addValidation("Classificação", new TRequiredValidator()); 

        $cabecalho_triagem->setAction(new TAction(['CabecalhoTriagemFormView', 'onShow']));
        $cabecalho_triagem->setId('b65cf39b3e4361');
        $cabecalho_triagem->hide();
        $carater_id->enableSearch();
        $triagem_anexo_triagem_path->enableFileHandling();
        $triagem_anexo_triagem_path->setLimitUploadSize(10);
        $triagem_anexo_triagem_path->setAllowedExtensions(["pdf","jpeg","jpg","png"]);
        $atendimento_servico_id->setValueSeparator(',');
        $encaminha_id->setMinLength(2);
        $triagem_procedimento_triagem_procedimento_id->setMinLength(2);

        $encaminha_id->setMask('{nome} - {conselho->sigla} {num_registro}');
        $triagem_procedimento_triagem_procedimento_id->setMask('{codigo} {nome}');

        $encaminha_id->setFilterColumns(["cns","cpf","nis","num_registro","nome"]);
        $triagem_procedimento_triagem_procedimento_id->setFilterColumns(["codigo","nome"]);

        $hgt_hi->setBreakItems(2);
        $hgt_jejum->setBreakItems(2);
        $atendimento_servico_id->setBreakItems(3);

        $imc->setEditable(false);
        $alergia_obs->setEditable(false);
        $comorbidade_obs->setEditable(false);
        $imc_classificacao->setEditable(false);

        $dor->setValue($param["dor"] ?? "N");
        $carater_id->setValue($param["2"] ?? "");
        $alergia->setValue($param["alergia"] ?? "N");
        $desfecho->setValue($param["desfecho"] ?? 2);
        $comorbidade->setValue($param["comorbidade"] ?? "N");
        $classificacao_id->setValue($param["classificacao"] ?? 6);

        $dor->addItems(["Y"=>" Sim","N"=>" Não"]);
        $hgt_hi->addItems(["Y"=>"Sim","N"=>"Não"]);
        $hgt_lo->addItems(["Y"=>"Sim","N"=>"Não"]);
        $alergia->addItems(["Y"=>"Sim","N"=>"Não"]);
        $hgt_jejum->addItems(["Y"=>"Sim","N"=>"Não"]);
        $comorbidade->addItems(["Y"=>"Sim","N"=>"Não"]);
        $desfecho->addItems(["1"=>" Liberar Cidadão","2"=>" Manter cidadão na lista de atendimentos","3"=>" Desistiu/Não aguardou"]);

        $dor->setUseButton();
        $hgt_hi->setUseButton();
        $hgt_lo->setUseButton();
        $alergia->setUseButton();
        $hgt_jejum->setUseButton();
        $comorbidade->setUseButton();
        $classificacao_id->setUseButton();

        $dor->setLayout('horizontal');
        $hgt_hi->setLayout('horizontal');
        $hgt_lo->setLayout('horizontal');
        $desfecho->setLayout('vertical');
        $alergia->setLayout('horizontal');
        $hgt_jejum->setLayout('horizontal');
        $comorbidade->setLayout('horizontal');
        $classificacao_id->setLayout('horizontal');
        $atendimento_servico_id->setLayout('horizontal');

        $dor->setSize(70);
        $id->setSize(200);
        $imc->setSize('33%');
        $hgt_hi->setSize(70);
        $hgt_lo->setSize(70);
        $fc->setSize('100%');
        $fr->setSize('100%');
        $hgt->setSize('100%');
        $alergia->setSize(70);
        $cbo_id->setSize(200);
        $peso->setSize('100%');
        $hgt_jejum->setSize(70);
        $local_id->setSize(200);
        $altura->setSize('100%');
        $pessoa_id->setSize(200);
        $comorbidade->setSize(70);
        $dt_triagem->setSize(200);
        $obs->setSize('100%', 150);
        $situacao_id->setSize(200);
        $desfecho->setSize('100%');
        $saturacao->setSize('100%');
        $carater_id->setSize('100%');
        $queixa->setSize('100%', 200);
        $temperatura->setSize('100%');
        $alergia_obs->setSize('100%');
        $atendimento_id->setSize(200);
        $profissional_id->setSize(200);
        $encaminha_id->setSize('100%');
        $classificacao_id->setSize(100);
        $comorbidade_obs->setSize('100%');
        $imc_classificacao->setSize('63%');
        $desfecho_obs->setSize('100%', 70);
        $cabecalho_triagem->setSize('100%');
        $atendimento_servico_id->setSize(300);
        $perimetro_encefalico->setSize('100%');
        $pa_sistolica->setSize('calc(50% - 20px)');
        $pa_distolica->setSize('calc(50% - 20px)');
        $triagem_anexo_triagem_path->setSize('100%');
        $triagem_anexo_triagem_descricao->setSize('100%');
        $triagem_procedimento_triagem_procedimento_id->setSize('100%');

        $imc_classificacao->style = "color: red !important;";

        $loadingContainer = new TElement('div');
        $loadingContainer->style = 'text-align:center; padding:50px';

        $icon = new TElement('i');
        $icon->class = 'fas fa-spinner fa-spin fa-3x';

        $loadingContainer->add($icon);
        $loadingContainer->add('<br>Carregando');

        $cabecalho_triagem->add($loadingContainer);

        $this->cabecalho_triagem = $cabecalho_triagem;

        //<onBeforeAddFieldsToForm>

        //</onBeforeAddFieldsToForm>

        $container_dados = new BContainer('container_dados');
        $this->container_dados = $container_dados;

        $container_dados->setTitle("Dados Paciente", '#333', '18px', 'B', '#fff');
        $container_dados->setBorderColor('#c0c0c0');
        $container_dados->enableExpander();
        $container_dados->enableStartExpanderOpened();

        $row1 = $container_dados->addFields([$cabecalho_triagem]);
        $row1->layout = [' col-sm-12'];

        $row2 = $this->form->addFields([$container_dados]);
        $row2->layout = [' col-sm-12'];

        $container_principal = new BContainer('container_principal');
        $this->container_principal = $container_principal;

        $container_principal->setTitle("Queixa Principal", '#333', '18px', 'B', '#fff');
        $container_principal->setBorderColor('#c0c0c0');
        $container_principal->enableExpander();
        $container_principal->enableStartExpanderOpened();

        $row3 = $container_principal->addFields([new TLabel("* Campo Obrigatório", '#F44336', '14px', 'I'),$queixa]);
        $row3->layout = [' col-sm-12'];

        $row4 = $container_principal->addFields([$carater_id,new TLabel("Caráter de Atendimento:", null, '14px', null, '100%')]);
        $row4->layout = ['col-sm-12'];

        $row5 = $this->form->addFields([$container_principal]);
        $row5->layout = [' col-sm-12'];

        $bcontainer_65c662569bbe4 = new BContainer('bcontainer_65c662569bbe4');
        $this->bcontainer_65c662569bbe4 = $bcontainer_65c662569bbe4;

        $bcontainer_65c662569bbe4->setTitle("Avaliação", '#333', '18px', 'B', '#fff');
        $bcontainer_65c662569bbe4->setBorderColor('#c0c0c0');
        $bcontainer_65c662569bbe4->enableExpander();
        $bcontainer_65c662569bbe4->enableStartExpanderOpened();

        $row6 = $bcontainer_65c662569bbe4->addFields([new TLabel("Peso (Kg):", null, '14px', null, '100%'),$peso],[new TLabel("Altura (cm):", null, '14px', null, '100%'),$altura],[new TLabel("IMC:", null, '14px', null, '100%'),$imc,$imc_classificacao],[new TLabel("Temperatura:", null, '14px', null, '100%'),$temperatura],[new TLabel("Perímetro Encefálico:", null, '14px', null),$perimetro_encefalico]);
        $row6->layout = ['col-sm-2','col-sm-2','col-sm-4','col-sm-2','col-sm-2'];

        $row7 = $bcontainer_65c662569bbe4->addFields([new TLabel("HGT Jejum:", null, '14px', null, '100%'),$hgt_jejum],[new TLabel("HGT HI:", null, '14px', null, '100%'),$hgt_hi],[new TLabel("HGT LO:", null, '14px', null, '100%'),$hgt_lo],[new TLabel("HGT:", null, '14px', null, '100%'),$hgt],[new TLabel("Dor:", null, '14px', null, '100%'),$dor]);
        $row7->layout = [' col-sm-2',' col-sm-2','col-sm-2','col-sm-2','col-sm-2'];

        $row8 = $bcontainer_65c662569bbe4->addFields([new TLabel("Pressão Arterial:", null, '14px', null, '100%'),$pa_sistolica,new TLabel("X", null, '14px', null),$pa_distolica],[new TLabel("Saturação (%):", null, '14px', null, '100%'),$saturacao],[new TLabel("FC:", null, '14px', null, '100%'),$fc],[new TLabel("FR:", null, '14px', null, '100%'),$fr]);
        $row8->layout = ['col-sm-4','col-sm-2','col-sm-2','col-sm-2'];

        $row9 = $bcontainer_65c662569bbe4->addFields([new TLabel("Possui Comorbidades?", null, '14px', null, '100%'),$comorbidade],[new TLabel("Quais:", null, '14px', null, '100%'),$comorbidade_obs]);
        $row9->layout = ['col-sm-2','col-sm-10'];

        $row10 = $bcontainer_65c662569bbe4->addFields([new TLabel("Possui Alergias?", null, '14px', null, '100%'),$alergia],[new TLabel("Quais:", null, '14px', null, '100%'),$alergia_obs]);
        $row10->layout = ['col-sm-2',' col-sm-10'];

        $row11 = $this->form->addFields([$bcontainer_65c662569bbe4]);
        $row11->layout = [' col-sm-12'];

        $bcontainer_65c660bc9bbdb = new BContainer('bcontainer_65c660bc9bbdb');
        $this->bcontainer_65c660bc9bbdb = $bcontainer_65c660bc9bbdb;

        $bcontainer_65c660bc9bbdb->setTitle("Procedimentos", '#333', '18px', 'B', '#fff');
        $bcontainer_65c660bc9bbdb->setBorderColor('#c0c0c0');
        $bcontainer_65c660bc9bbdb->enableExpander();

        $row12 = $bcontainer_65c660bc9bbdb->addFields([$this->list_procedimentos]);
        $row12->layout = [' col-sm-12'];

        $row13 = $this->form->addFields([$bcontainer_65c660bc9bbdb]);
        $row13->layout = [' col-sm-12'];

        $bcontainer_65c6601e5fef2 = new BContainer('bcontainer_65c6601e5fef2');
        $this->bcontainer_65c6601e5fef2 = $bcontainer_65c6601e5fef2;

        $bcontainer_65c6601e5fef2->setTitle("Observação/Anotação", '#333', '18px', 'B', '#fff');
        $bcontainer_65c6601e5fef2->setBorderColor('#c0c0c0');
        $bcontainer_65c6601e5fef2->enableExpander();

        $row14 = $bcontainer_65c6601e5fef2->addFields([$obs]);
        $row14->layout = [' col-sm-12'];

        $row15 = $this->form->addFields([$bcontainer_65c6601e5fef2]);
        $row15->layout = [' col-sm-12'];

        $container_anexo = new BContainer('container_anexo');
        $this->container_anexo = $container_anexo;

        $container_anexo->setTitle("Anexos", '#333', '18px', 'B', '#fff');
        $container_anexo->setBorderColor('#c0c0c0');
        $container_anexo->setId('anexo_container');
        $container_anexo->enableExpander();

        $row16 = $container_anexo->addFields([$this->list_anexos]);
        $row16->layout = [' col-sm-12'];

        $row17 = $this->form->addFields([$container_anexo]);
        $row17->layout = [' col-sm-12'];

        $container_finaliza = new BContainer('container_finaliza');
        $this->container_finaliza = $container_finaliza;

        $container_finaliza->setTitle("Finalização do Atendimento", '#333', '18px', 'B', '#fff');
        $container_finaliza->setBorderColor('#c0c0c0');
        $container_finaliza->setId('finaliza_container');
        $container_finaliza->enableExpander();
        $container_finaliza->enableStartExpanderOpened();

        $c_classificacao = new BContainer('c_classificacao');
        $this->c_classificacao = $c_classificacao;

        $c_classificacao->setTitle("Classificação", '#FFFFFF', '18px', 'B', '#F44336');
        $c_classificacao->setBorderColor('#c0c0c0');
        $c_classificacao->setId('classificacao_container');
        $c_classificacao->enableExpander();
        $c_classificacao->enableStartExpanderOpened();

        $row18 = $c_classificacao->addFields([$classificacao_id]);
        $row18->layout = [' col-sm-12'];

        $row19 = $container_finaliza->addFields([$c_classificacao]);
        $row19->layout = [' col-sm-12'];

        $row20 = $container_finaliza->addFields([$id,$pessoa_id,$dt_triagem,$situacao_id,$atendimento_id,$profissional_id,$cbo_id]);
        $row20->layout = [' col-sm-12'];

        $bcontainer_6837386e4aaef = new BContainer('bcontainer_6837386e4aaef');
        $this->bcontainer_6837386e4aaef = $bcontainer_6837386e4aaef;

        $bcontainer_6837386e4aaef->setTitle("Encaminhamentos", '#000000', '18px', '', '#FFFFFF');
        $bcontainer_6837386e4aaef->setBorderColor('#C0C0C0');
        $bcontainer_6837386e4aaef->enableExpander();
        $bcontainer_6837386e4aaef->enableStartExpanderOpened();

        $row21 = $bcontainer_6837386e4aaef->addFields([$atendimento_servico_id]);
        $row21->layout = [' col-sm-12'];

        $row22 = $container_finaliza->addFields([$bcontainer_6837386e4aaef]);
        $row22->layout = [' col-sm-12'];

        $c_desfecho = new BContainer('c_desfecho');
        $this->c_desfecho = $c_desfecho;

        $c_desfecho->setTitle("Desfecho", '#FFFFFF', '18px', 'B', '#3F51B5');
        $c_desfecho->setBorderColor('#C0C0C0');
        $c_desfecho->setId('desfecho_container');
        $c_desfecho->enableExpander();
        $c_desfecho->enableStartExpanderOpened();

        $row23 = $c_desfecho->addFields([$desfecho]);
        $row23->layout = [' col-sm-12'];

        $row24 = $container_finaliza->addFields([$c_desfecho]);
        $row24->layout = [' col-sm-12'];

        $c_profissional = new BContainer('c_profissional');
        $this->c_profissional = $c_profissional;

        $c_profissional->setTitle("Profissional", '#FFFFFF', '18px', 'B', '#2196F3');
        $c_profissional->setBorderColor('#c0c0c0');
        $c_profissional->setId('profissional_container');
        $c_profissional->enableExpander();
        $c_profissional->enableStartExpanderOpened();

        $row25 = $c_profissional->addFields([$local_id,$encaminha_id]);
        $row25->layout = [' col-sm-12'];

        $row26 = $container_finaliza->addFields([$c_profissional]);
        $row26->layout = [' col-sm-12'];

        $c_obs = new BContainer('c_obs');
        $this->c_obs = $c_obs;

        $c_obs->setTitle("Anotação/Observação", '#333', '18px', 'B', '#fff');
        $c_obs->setBorderColor('#c0c0c0');
        $c_obs->setId('obs_container');
        $c_obs->enableExpander();
        $c_obs->enableStartExpanderOpened();

        $row27 = $c_obs->addFields([$desfecho_obs]);
        $row27->layout = [' col-sm-12'];

        $row28 = $container_finaliza->addFields([$c_obs]);
        $row28->layout = [' col-sm-12'];

        $row29 = $this->form->addFields([$container_finaliza]);
        $row29->layout = [' col-sm-12'];

        //<onAfterFieldsCreation>

        //</onAfterFieldsCreation>

        // create the form actions
        $btn_onsave = $this->form->addAction("Finalizar Atentimento", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_oncancelatriagem = $this->form->addAction("Suspender Triagem", new TAction([$this, 'onCancelaTriagem']), 'fas:window-close #FFFFFF');
        $this->btn_oncancelatriagem = $btn_oncancelatriagem;
        $btn_oncancelatriagem->addStyleClass('btn-danger'); 

        $btn_oncall = $this->form->addHeaderAction("Chamar Paciente", new TAction([$this, 'onCall']), 'fas:bullhorn #FFFFFF');
        $this->btn_oncall = $btn_oncall;
        $btn_oncall->addStyleClass('btn-success');        $btn_oncallcancelatriagem = $this->form->addHeaderAction("Suspender a Triagem", new TAction([$this, 'onCallCancelaTriagem']), 'fas:window-close #FFFFFF');
        $this->btn_oncallcancelatriagem = $btn_oncallcancelatriagem;
        $btn_oncallcancelatriagem->addStyleClass('btn-danger'); 

        // Add clear button (referenced later in the code)
        $btn_onclear = $this->form->addAction("Limpar", new TAction([$this, 'onClear']), 'fas:eraser #FFFFFF');
        $this->btn_onclear = $btn_onclear;
        $btn_onclear->addStyleClass('btn-secondary');

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        //<onAfterPageCreation>

        $this->btn_onclear->style = 'display:none'; //oculta o botão Limpa formulário
        $btnClose->style = 'display:none';

        if(!empty($imc_classficacao))
        {
          $imc_classficacao->setProperty("style", "color:red !important;");
        }

        //</onAfterPageCreation>

        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=TriagemForm]');
        $style->width = '90% !important';   
        $style->show(true);

    }

    //<generated-exitAction-onCalcIMC>
    public static function onCalcIMC($param = null) 
    {
        try 
        {

            $peso = (double) str_replace(',', '.', str_replace('.', '', $param['peso']));
            $altura_cm = (double) str_replace(',', '.', str_replace('.', '', $param['altura']));

            if ($peso > 0 && $altura_cm > 0) 
            {
                $altura = $altura_cm / 100;

                $imc = $peso / ($altura * $altura);

                if ($imc < 18.5) {
                    $classificacao = 'Magreza';
                } elseif ($imc < 25) {
                    $classificacao = 'Normal';
                } elseif ($imc < 30) {
                    $classificacao = 'Sobrepeso';
                } elseif ($imc < 35) {
                    $classificacao = 'Obesidade Grau I';
                } elseif ($imc < 40) {
                    $classificacao = 'Obesidade Grau II';
                } else {
                    $classificacao = 'Obesidade Grau III (Mórbida)';
                }

                $object = new stdClass();
                $object->imc = number_format($imc, 1, ',', '.');
                $object->imc_classificacao = "{$classificacao}";

                TForm::sendData(self::$formName, $object);
                return $object;
            }
            else 
            {
                $object = new stdClass();
                $object->imc = '';
                $object->imc_classificacao = '';
                TForm::sendData(self::$formName, $object);
            }
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-exitAction-onCalcIMC>

    //<generated-exitAction-onReload>
    public static function onReload($param = null) 
    {
        try 
        {

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-exitAction-onReload>

    //<generated-changeAction-onGlicemia>
    public static function onGlicemia($param = null) 
    {
        try 
        {
            //code here
            if( $param['hgt_hi'] == "Y")
            {
                // Código gerado pelo snippet: "Desabilitar campo"
                TEntry::disableField(self::$formName, 'hgt');
                // Código gerado pelo snippet: "Enviar dados para campo"
                $object = new stdClass();
                $object->hgt = '';
                //$object->fieldName = 'insira o novo valor aqui'; //sample

                TForm::sendData(self::$formName, $object);
                // -----

                // -----
            } else {
                // Código gerado pelo snippet: "Habilitar campo"
                TEntry::enableField(self::$formName, 'hgt');
                // -----
            } 

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-changeAction-onGlicemia>

    //<generated-changeAction-onHGT>
    public static function onHGT($param = null) 
    {
        try 
        {
            //code here
            if( $param['hgt_lo'] == "Y")
            {
                // Código gerado pelo snippet: "Desabilitar campo"
                TEntry::disableField(self::$formName, 'hgt');
                // Código gerado pelo snippet: "Enviar dados para campo"
                $object = new stdClass();
                $object->hgt = '';
                //$object->fieldName = 'insira o novo valor aqui'; //sample

                TForm::sendData(self::$formName, $object);
                // -----

                // -----
            } else {
                // Código gerado pelo snippet: "Habilitar campo"
                TEntry::enableField(self::$formName, 'hgt');
                // -----
            } 

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-changeAction-onHGT>

    //<generated-changeAction-onHabilitaComorbidade>
    public static function onHabilitaComorbidade($param = null) 
    {
        try 
        {
            //code here
            if( $param['comorbidade'] == "N")
            {
                // Código gerado pelo snippet: "Desabilitar campo"
                TEntry::disableField(self::$formName, 'comorbidade_obs');
                // -----
            } else {
                // Código gerado pelo snippet: "Habilitar campo"
                TEntry::enableField(self::$formName, 'comorbidade_obs');
                // -----
            }

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-changeAction-onHabilitaComorbidade>

    //<generated-changeAction-onHabilitaAlergia>
    public static function onHabilitaAlergia($param = null) 
    {
        try 
        {
            //code here
            if( $param['alergia'] == "N")
            {
                // Código gerado pelo snippet: "Desabilitar campo"
                TEntry::disableField(self::$formName, 'alergia_obs');
                // -----
            } else {
                // Código gerado pelo snippet: "Habilitar campo"
                TEntry::enableField(self::$formName, 'alergia_obs');
                // -----
            } 

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-changeAction-onHabilitaAlergia>

//<generated-FormAction-onSave>
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Triagem(); // create an empty object //</blockLine>

            $data = $this->form->getData(); 

            $object->fromArray( (array) $data);

            $pessoa = new Pessoa($object->pessoa_id); 
            $object->idade = $pessoa->idade_ano;

            //</beforeStoreAutoCode> //</blockLine> 
//<generatedAutoCode>

            $triagem_anexo_triagem_path_dir = 'app/output/triagem';
//</generatedAutoCode> 

            $object->system_unit_id = TSession::getValue('userunitid');

            $object->mes = date('m');
            $object->ano = date('Y');

            //valida quando a queixa está vazia e não há desistência
            if( empty( $data->queixa ) && $data->desfecho != 3 )
            {
                throw new Exception("Informe a queixa principal");
            }

            //valida quando há desistência e não é informado uma condição ou situação
            if( ( strlen($data->desfecho_obs) < 4 ) && $data->desfecho == 3 )
            {
                $object->desfecho_obs = "Profissional não informou o motivo da desistência do paciente.";
            }

            // Valida se o desfecho for "Manter na lista de atendimentos", exige pelo menos um serviço selecionado
            if ($data->desfecho == '2' && empty($data->atendimento_servico_id)) {
               // throw new Exception("É obrigatório selecionar pelo menos um serviço de atendimento quando o cidadão for mantido na lista de atendimentos.");
            }

            $object->store(); // save the object //</blockLine>

            if(!empty( $data->atendimento_id ) && empty( $data->id ))
            {
                $triagemAtendimento = new TriagemAtendimento();
                $triagemAtendimento->atendimento_id = $data->atendimento_id;
                $triagemAtendimento->triagem_id = $object->id;
                $triagemAtendimento->store();
            }
            //</afterStoreAutoCode> //</blockLine>

    //<fieldList-2140255-17361465> //</hideLine>
//<generatedAutoCode>
            $this->criteria_list_anexos->setProperty('order', 'id asc');
//</generatedAutoCode>
            $triagem_anexo_triagem_items = $this->storeItems('TriagemAnexo', 'triagem_id', $object, $this->list_anexos, function($masterObject, $detailObject){ //</blockLine>

                //code here

                //</autoCode>
            }, $this->criteria_list_anexos); //</blockLine>
    //</hideLine> //</fieldList-2140255-17361465>
//<generatedAutoCode>
            if(!empty($triagem_anexo_triagem_items))
            {
                foreach ($triagem_anexo_triagem_items as $item)
                {
                    $dataFile = new stdClass();
                    $dataFile->path = $item->path;
                    $this->saveFile($item, $dataFile, 'path', $triagem_anexo_triagem_path_dir);
                }
            }

//</generatedAutoCode>

    //<fieldList-807676-7542814> //</hideLine>
//<generatedAutoCode>
            $this->criteria_list_procedimentos->setProperty('order', 'id asc');
//</generatedAutoCode>
            $triagem_procedimento_triagem_items = $this->storeItems('TriagemProcedimento', 'triagem_id', $object, $this->list_procedimentos, function($masterObject, $detailObject){ //</blockLine>

                //code here

                //</autoCode>
            }, $this->criteria_list_procedimentos); //</blockLine>
    //</hideLine> //</fieldList-807676-7542814>

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; //</blockLine>

            $this->form->setData($data); // fill form data

            // Processa o atendimento baseado no desfecho do objeto 
            $this->processaAtendimento($object);

            $pageParam = []; // ex.: = ['key' => 10]

            // Redireciona para a Fila, apontando para o método onReload ao invés do onShow, que é o padrão
            TApplication::loadPage('AtendimentoProfissionalList', 'onReload', $pageParam);

            TTransaction::close();

            //</messageAutoCode> //</blockLine>
//<generatedAutoCode>
            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
//</generatedAutoCode>

            //</endTryAutoCode> //</blockLine>
//<generatedAutoCode>
            TScript::create("Template.closeRightPanel();");
            TForm::sendData(self::$formName, (object)['id' => $object->id]);

//</generatedAutoCode>

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> //</blockLine> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
//</generated-FormAction-onSave>
//<generated-FormAction-onCancelaTriagem>
    public function onCancelaTriagem($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database);

            $triagem = new Triagem();

            $data = $this->form->getData(); // get form data as array
            $triagem->fromArray( (array) $data); // load the object with data

            $triagem_anexo_triagem_path_dir = 'app/output/triagem';

            $triagem->store();

            $this->criteria_list_anexos->setProperty('order', 'id asc');

            $triagem_anexo_triagem_items = $this->storeItems('TriagemAnexo', 'triagem_id', $triagem, $this->list_anexos, function($masterObject, $detailObject){ 

            }, $this->criteria_list_anexos); 

            if(!empty($triagem_anexo_triagem_items))
            {
                foreach ($triagem_anexo_triagem_items as $item)
                {
                    $dataFile = new stdClass();
                    $dataFile->path = $item->path;
                    $this->saveFile($item, $dataFile, 'path', $triagem_anexo_triagem_path_dir);
                }
            }

            $triagemAtendimento = AtendimentoService::retornaTriagemAtendimento( $triagem->id );

            if( in_array($data->situacao_id, [SituacaoConsulta::EM_ATENDIMENTO, SituacaoConsulta::CHAMADO, SituacaoConsulta::SUSPENSO, SituacaoConsulta::CANCELADO]))
            {
                AtendimentoService::atualizaSituacaoTriagem( $triagem->id, SituacaoConsulta::SUSPENSO );
                AtendimentoService::atualizaSituacaoAtendimento( $triagemAtendimento->atendimento_id, AtendimentoSituacao::AGUARDANDO );
                AtendimentoService::registraUsuarioAtendimento( $triagemAtendimento->atendimento_id, 'T', 'N');
                AtendimentoService::registraHistoricoAtendimento( $triagemAtendimento->atendimento_id, TipoHistorico::TRIAGEM, AtendimentoSituacao::CANCELADO, ">>SUSPENSA TRIAGEM<<");

            } 

            if( $data->situacao_id == SituacaoConsulta::ENCAMINHA )
            {
                 AtendimentoService::atualizaSituacaoAtendimento( $triagemAtendimento->atendimento_id, AtendimentoSituacao::AGUARDANDO );
            }

            TTransaction::close();
            $pageParam = []; // ex.: = ['key' => 10]

            TApplication::loadPage('AtendimentoProfissionalList', 'onShow', $pageParam);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }//</end>
//</generated-FormAction-onCancelaTriagem>
//<generated-FormAction-onCall>
    public function onCall($param = null) 
    {
        try 
        {
            if (isset($param) and !empty($param['key']))
            {
                TTransaction::open(self::$database);

                $atendimento = new Atendimento($param['key']);

                $profissional = Pessoa::where('system_user_id', '=', TSession::getValue('userid'))->first();

                // definição do local do atendimento 
                $local = LocalProfissional::where('profissional_id', '=', $profissional->id)
                                        ->where('system_unit_id', '=', TSession::getValue('userunitid'))
                                        ->where('ativo', '=', 'Y')
                                        ->first();

                if (!$local) {
                    // Código gerado pelo snippet: "Mensagem Toast"
                    TToast::show("error", "Nenhum local de atendimento foi definido.", "topCenter", "far:window-close");
                    return;
                }

                AtendimentoService::registraHistoricoAtendimento(
                    $atendimento->id,
                    TipoHistorico::RECEPCAO,
                    AtendimentoSituacao::CHAMADO,
                    ">>PACIENTE CHAMADO <<"
                );

                TTransaction::close();

                if ($atendimento)
                {
                    TTransaction::open('painel');

                    $chamada = new Chamado();
                    $chamada->profissional_id  = $profissional->id;
                    $chamada->paciente_id      = $atendimento->paciente_id;
                    $chamada->local_id         = $local->local_id;
                    $chamada->system_unit_id   = TSession::getValue('userunitid');
                    $chamada->classificacao_id = $atendimento->classificacao_id;
                    $chamada->dt_atendimento   = date('Y-m-d');
                    $chamada->situacao         = 0; // NÃO FOI CHAMADO PELO AUDIO AINDA
                    $chamada->store();

                    TToast::show(
                        'success',
                        "O (a) paciente {$chamada->paciente->nome} chamado com sucesso!",
                        'top right',
                        'far:check-circle'
                    );

                    TTransaction::close();
                }

                SenhaService::mudaSituacao(
                    $atendimento->id,
                    AtendimentoSituacao::CHAMADO,
                    $profissional,
                    $local->local_id
                );
            }

            $this->onReload($param);
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }//</end>
//</generated-FormAction-onCall>
//<generated-FormAction-onCallCancelaTriagem>
    public function onCallCancelaTriagem($param = null) 
    {
        try 
        {
            $this->onCancelaTriagem($param); 
            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }//</end>
//</generated-FormAction-onCallCancelaTriagem>

//<generated-onEdit>
    public function onEdit( $param )//</ini>
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Triagem($key); // instantiates the Active Record //</blockLine>

                //$this->inEdition = true;

                //</beforeSetDataAutoCode> //</blockLine>
//<generatedAutoCode>
                $this->cabecalho_triagem->unhide();
                $this->cabecalho_triagem->setParameter('key', $object->id);

//</generatedAutoCode>

    //<fieldList-2140255-17361465> //</hideLine>
                $this->criteria_list_anexos->setProperty('order', 'id asc');//</blockLine>
                $this->list_anexos_items = $this->loadItems('TriagemAnexo', 'triagem_id', $object, $this->list_anexos, function($masterObject, $detailObject, $objectItems){ //</blockLine>

                    //code here

                    //</autoCode>
                }, $this->criteria_list_anexos); //</blockLine>
    //</hideLine> //</fieldList-2140255-17361465>

    //<fieldList-807676-7542814> //</hideLine>
                $this->criteria_list_procedimentos->setProperty('order', 'id asc');//</blockLine>
                $this->list_procedimentos_items = $this->loadItems('TriagemProcedimento', 'triagem_id', $object, $this->list_procedimentos, function($masterObject, $detailObject, $objectItems){ //</blockLine>

                    //code here

                    //</autoCode>
                }, $this->criteria_list_procedimentos); //</blockLine>
    //</hideLine> //</fieldList-807676-7542814>

                $array = $object->toArray();

                // === CÁLCULO DO IMC E CLASSIFICAÇÃO ===
            if (!empty($object->peso) && !empty($object->altura))
            {
                $peso = (double) str_replace(',', '.', str_replace('.', '', $object->peso));
                $altura_cm = (double) str_replace(',', '.', str_replace('.', '', $object->altura));

                if ($peso > 0 && $altura_cm > 0)
                {
                    $altura = $altura_cm / 100;
                    $imc = $peso / ($altura * $altura);

                    if ($imc < 18.5) {
                        $classificacao = 'Magreza';
                    } elseif ($imc < 25) {
                        $classificacao = 'Normal';
                    } elseif ($imc < 30) {
                        $classificacao = 'Sobrepeso';
                    } elseif ($imc < 35) {
                        $classificacao = 'Obesidade Grau I';
                    } elseif ($imc < 40) {
                        $classificacao = 'Obesidade Grau II';
                    } else {
                        $classificacao = 'Obesidade Grau III (Mórbida)';
                    }

                    $object->imc = number_format($imc, 1, ',', '.');
                    $object->imc_classificacao = $classificacao;
                }
            }

                self::onHabilitaAlergia( $array );
                self::onHabilitaComorbidade( $array );
               // self::onExibeEncaminha( $array );
                self::onGlicemia( $array );

                //registra o histórico de atendimento
                AtendimentoService::registraHistoricoAtendimento( $object->id, TipoHistorico::TRIAGEM, $object->situacao_id, "{$object->profissional->conselho->sigla} {$object->profissional->num_registro} fez atualizações.");

                $this->form->setData($object); // fill the form //</blockLine>

                //</afterSetDataAutoCode> //</blockLine>
//<generatedAutoCode>

//</generatedAutoCode>

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }//</end>
//</generated-onEdit>

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        $this->list_procedimentos->addHeader();
        $this->list_procedimentos->addDetail($this->default_item_list_procedimentos);

        $this->list_procedimentos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        $this->list_anexos->addHeader();
        $this->list_anexos->addDetail($this->default_item_list_anexos);

        $this->list_anexos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        //<onFormClear>

        //</onFormClear>

    }

    public function onShow($param = null)
    {
        $this->list_procedimentos->addHeader();
        $this->list_procedimentos->addDetail($this->default_item_list_procedimentos);

        $this->list_procedimentos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        $this->list_anexos->addHeader();
        $this->list_anexos->addDetail($this->default_item_list_anexos);

        $this->list_anexos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        //<onShow>

        //</onShow>
    } 

    public static function getFormName()
    {
        return self::$formName;
    }

    //</hideLine> <addUserFunctionsCode/>

     //<generated-userFunction-onLoad>
    public  function onLoad($param = null) 
    {
        try 
        {

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-userFunction-onLoad>

     //<generated-userFunction-autoSave>
    public  function autoSave($param = null) 
    {
        try 
        {
            TTransaction::open(self::$database); // open a transaction

            $object = new Triagem();

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->system_unit_id = TSession::getValue('userunitid');

            $object->mes = date('m');
            $object->ano = date('Y');            

            $object->store();

            if(!empty( $data->atendimento_id ) && empty( $data->id ))
            {
                $triagemAtendimento = new TriagemAtendimento();
                $triagemAtendimento->atendimento_id = $data->atendimento_id;
                $triagemAtendimento->triagem_id = $object->id;
                $triagemAtendimento->store();
            }

            $this->criteria_list_procedimentos->setProperty('order', 'id asc');

	        $triagem_procedimento_triagem_items = $this->storeItems('TriagemProcedimento', 'triagem_id', $object, $this->list_procedimentos, function($masterObject, $detailObject){ 

            }, $this->criteria_list_procedimentos); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data

            TTransaction::close(); // close the transaction

           // TForm::sendData(self::$formName, (object)['id' => $object->id]);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
//</generated-userFunction-autoSave>

    //<userCustomFunctions>

    /**
     * Finaliza o atendimento, atualizando sua situação e registrando o histórico.
     *
     * @param Atendimento $atendimento Objeto de atendimento a ser finalizado
     * @param object $object Dados do atendimento com informações sobre o desfecho e profissional
     */
    private function finalizaAtendimento($atendimento, $object)
    {
        // Atualiza a situação do atendimento para 'FINALIZADO'
        AtendimentoService::atualizaSituacaoAtendimento($atendimento->id, AtendimentoSituacao::FINALIZADO);

        // Atualiza a situação da triagem para 'FINALIZADO'
        AtendimentoService::atualizaSituacaoTriagem($object->id, SituacaoConsulta::FINALIZA);

        // Define a mensagem de texto da tramitação 
        $mensagem = "{$object->profissional->conselho->sigla} {$object->profissional->num_registro} encerrou o atendimento.";

        // Registra o histórico do atendimento indicando o encerramento
        AtendimentoService::registraHistoricoAtendimento( $atendimento->id, TipoHistorico::TRIAGEM, AtendimentoSituacao::FINALIZADO, $mensagem );
    }

    /**
     * Encaminha o atendimento para outro profissional ou sala, atualizando a situação e registrando o histórico.
     *
     * @param Atendimento $atendimento Objeto de atendimento a ser encaminhado
     * @param object $object Dados do atendimento com informações sobre o desfecho e profissional
     * @param object $triagemAtendimento Informações da triagem associada ao atendimento
     */
    private function encaminhaAtendimento($atendimento, $object, $triagemAtendimento)
    {
        // Verifica se há um profissional específico para encaminhamento
        if (empty($object->encaminha_id)) 
        {
            $atendimento->profissional_id = null;
            $atendimento->local_id = 1; // Sala padrão se não especificado
        } else {
            // Define o profissional e sala para onde será encaminhado
            $atendimento->profissional_id = $object->encaminha_id;
            $atendimento->local_id = AtendimentoService::pegaSalaProfissional($object->encaminha_id);
            $atendimento->cbo_id = AtendimentoService::retornaCBOProfissional($object->encaminha_id);
        }

        // Define a situação do atendimento como 'AGUARDANDO' e armazena as mudanças
        $atendimento->classificacao_id = $object->classificacao_id;
        $atendimento->situacao_id = AtendimentoSituacao::AGUARDANDO;
        $atendimento->store();

        // Inicia uma consulta associada ao atendimento encaminhado
        AtendimentoService::iniciaConsulta($triagemAtendimento->triagem_id, $atendimento->id);

        // Atualiza a situação da triagem para 'ENCAMINHADO'
        AtendimentoService::atualizaSituacaoTriagem($object->id, SituacaoConsulta::ENCAMINHA);

        // Define o texto para tramitação 
        $mensagem = "{$object->profissional->conselho->sigla} {$object->profissional->num_registro} encaminhou o atendimento.";

        // Registra o histórico do atendimento indicando o encaminhamento
        AtendimentoService::registraHistoricoAtendimento($atendimento->id, TipoHistorico::TRIAGEM, AtendimentoSituacao::ENCAMINHADO, $mensagem );
    }

    /**
     * Cancela o atendimento, atualizando sua situação e registrando o histórico.
     *
     * @param Atendimento $atendimento Objeto de atendimento a ser cancelado
     * @param object $object Dados do atendimento com informações sobre o desfecho e profissional
     */
    private function cancelaAtendimento($atendimento, $object)
    {
        // Atualiza a situação do atendimento para 'FINALIZADO'
        AtendimentoService::atualizaSituacaoAtendimento($atendimento->id, AtendimentoSituacao::FINALIZADO);

        // Atualiza a situação da triagem para 'CANCELADO'
        AtendimentoService::atualizaSituacaoTriagem($object->id, SituacaoConsulta::CANCELADO);

        // Define o texto da tramitação 
        $mensagem = "{$object->profissional->conselho->sigla} {$object->profissional->num_registro} cancelou o atendimento.";

        // Registra o histórico do atendimento indicando o cancelamento
        AtendimentoService::registraHistoricoAtendimento( $atendimento->id, TipoHistorico::TRIAGEM, AtendimentoSituacao::CANCELADO, $mensagem );
    }

    /**
     * Processa um atendimento que foi encaminhado diretamente, criando um novo registro de atendimento.
     *
     * @param object $object Dados do atendimento encaminhado
     */
    private function processaAtendimentoEncaminhado($object)
    {
        // Verifica se o atendimento está marcado como 'ENCAMINHADO'
        if ($object->situacao_id == AtendimentoSituacao::ENCAMINHADO) 
        {
            // Recupera a sala e profissional para onde o atendimento foi encaminhado
            $sala_profissional = LocalProfissional::where('local_id', '=', $object->local_id)
                ->where('system_unit_id', '=', TSession::getValue('userunitid'))
                ->where('ativo', '=', 'Y')
                ->last();

            // Define o profissional responsável com base na sala, se encontrado
            $novoProfissional = $sala_profissional ? $sala_profissional->profissional_id : $object->profissional_id;

            // Cria um novo atendimento com os dados do encaminhamento
            $atendimento = new Atendimento();
            $atendimento->system_unit_id = TSession::getValue('userunitid');
            $atendimento->paciente_id = $object->pessoa_id;
            $atendimento->local_id = $object->local_id;
            $atendimento->profissional_id = $novoProfissional;
            $atendimento->situacao_id = AtendimentoSituacao::AGUARDANDO;
            $atendimento->classificacao_id = $object->classificacao_id;
            $atendimento->dt_atendimento = date('Y-m-d');
            $atendimento->recepcao = 'N';
            $atendimento->store();

            // Inicia uma consulta associada ao novo atendimento
            AtendimentoService::iniciaConsulta($object->id, $atendimento->id);
        }
    }

    /**
     * Processa o atendimento baseado no desfecho do objeto.
     *
     * @param object $object Dados do atendimento para processar
     */
    public function processaAtendimento($object)
    {
        // Verifica o histórico do atendimento, confirmando se a triagem foi realizada
        AtendimentoService::verificaHistorico($object, TipoHistorico::TRIAGEM); // Origem na Triagem

        // Recupera a triagem associada ao atendimento
        $triagemAtendimento = AtendimentoService::retornaTriagemAtendimento($object->id);

        if ($triagemAtendimento) 
        {
            // Instancia o atendimento associado à triagem
            $atendimento = new Atendimento($triagemAtendimento->atendimento_id);

            // Executa o método adequado com base no desfecho do atendimento
            switch ($object->desfecho) 
            {
                case '1':
                    $this->finalizaAtendimento($atendimento, $object);
                    break;

                case '2':
                    $this->encaminhaAtendimento($atendimento, $object, $triagemAtendimento);
                    break;

                case '3':
                    $this->cancelaAtendimento($atendimento, $object);
                    break;
            }

        } else {
            // Se não houver triagem associada, processa o atendimento como encaminhado
            $this->processaAtendimentoEncaminhado($object);
        }
    }

    //</userCustomFunctions>

}