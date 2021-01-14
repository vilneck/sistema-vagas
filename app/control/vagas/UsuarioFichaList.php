<?php

use Adianti\Widget\Datagrid\TDataGridActionGroup;
use Adianti\Widget\Form\TFile;

/**
 * SystemUserList
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class UsuarioFichaList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    protected $formgrid;
    protected $deleteButton;
    protected $transformCallback;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('permission');            // defines the database
        parent::setActiveRecord('UsuarioFichaView');   // defines the active record
        parent::setDefaultOrder('id', 'desc');         // defines the default order
        parent::addFilterField('id', '=', 'id'); // filterField, operator, formField
        parent::addFilterField('name', 'like', 'name'); // filterField, operator, formField
        parent::addFilterField('email', 'like', 'email'); // filterField, operator, formField
        parent::addFilterField('active', '=', 'active'); // filterField, operator, formField
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('flag_candidato','=',1));
        parent::setCriteria($criteria);
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_SystemUser');
        $this->form->setFormTitle('Candidatos');
        

        // create the form fields
        $id = new TEntry('id');
        $name = new TEntry('name');
        $email = new TEntry('email');
        $active = new TCombo('active');
        
        $active->addItems( [ 'Y' => _t('Yes'), 'N' => _t('No') ] );
        
        // add the fields
        $this->form->addFields( [new TLabel('Código')], [$id],[new TLabel(_t('Active'))], [$active]);
        $this->form->addFields( [new TLabel(_t('Name'))], [$name],[new TLabel(_t('Email'))], [$email] );
        
        $id->setSize('100%');
        $name->setSize('100%');
        $email->setSize('100%');
        $active->setSize('100%');
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('SystemUser_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
      //  $this->form->addAction(_t('New'),  new TAction(array('SystemUserForm', 'onEdit')), 'fa:plus green');
        $btn_onshow = $this->form->addAction("Novo Candidato", new TAction(['CandidatoForm', 'onClear']), 'fas:plus #69aa46');

        // creates a DataGrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        //$this->datagrid->datatable = 'true';
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'center', 50);
        $column_name = new TDataGridColumn('name', _t('Name'), 'left');
        $column_email = new TDataGridColumn('email', _t('Email'), 'left');
        $column_email = new TDataGridColumn('telefones', 'Telefones', 'left');
        $column_active = new TDataGridColumn('active', _t('Active'), 'center');
        $column_preencheu = new TDataGridColumn('preencheu_curriculo', "Preencheu Currículo?", 'center');
        
        $column_email->enableAutoHide(500);
        $column_active->enableAutoHide(500);
        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_name);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_preencheu);
        $this->datagrid->addColumn($column_active);

        $column_active->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });
        $column_preencheu->setTransformer(function($value, $object, $row) {
            $value = $object->get_preencheu_curriculo();
            $class = ($value==0) ? 'danger' : 'success';
            $label = ($value==0) ? _t('No') : _t('Yes');
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });
        
        // creates the datagrid column actions
        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);
        
        $order_name = new TAction(array($this, 'onReload'));
        $order_name->setParameter('order', 'name');
        $column_name->setAction($order_name);
        
        
        $order_email = new TAction(array($this, 'onReload'));
        $order_email->setParameter('order', 'email');
        $column_email->setAction($order_email);
        
        
        // create EDIT action
        $action_edit = new TDataGridAction(array('UsuarioFichaForm', 'onEdit'));
        $action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('far:edit blue');
        $action_edit->setField('id');                      
       
        $action_group = new TDataGridActionGroup("Ações");
        $action_group->addAction($action_edit);
        $this->datagrid->addActionGroup($action_group);
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid)->style = 'overflow-x:auto';
        $panel->addFooter($this->pageNavigation);
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(TBreadCrumb::create(["Candidatos"]));
        $container->add($this->form);
        $container->add($panel);
        
        parent::add($container);
    }
}
