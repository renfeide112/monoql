Ext.ns('monoql.form');
monoql.form.newconnectionform = function() {
	var cls = 'monoql-form-newconnectionform';
	
	var SaveButton = Ext.extend(monoql.button.button, {
		text:'Save',
		initComponent:function() {
			SaveButton.superclass.initComponent.call(this);
			this.addClass(cls + "-savebutton");
		}
	});
	
	var DatabaseTypeComboBox = Ext.extend(monoql.form.combobox, {
		triggerAction:'all',
		displayField:'text',
		valueField:'type',
		forceSelection:true,
		allowBlank:false,
		mode:'local',
		fieldLabel:'Type',
		hiddenName:'type',
		value:'mysql',
		editable:true,
		initComponent:function() {
			this.store = new Ext.data.JsonStore({
				fields:['type', 'text'],
				root:'records',
				data:{"records":[
					{"type":"mysql", "text":"MySQL"}
				]}
			});
			DatabaseTypeComboBox.superclass.initComponent.call(this);
			this.addClass(cls + "-databasetypecombobox");
		}
	});
	
	var Class = Ext.extend(monoql.form.floatingform, {
		title:'Add a new connection',
		labelAlign:'left',
		width:300,
		constructor: function(config) {
			var config = Ext.apply({
				api:{
					load:monoql.direct.Connection.formLoad,
					submit:monoql.direct.Connection.formCreate
				},
				paramsAsHash:true
			}, config || {});
			Class.superclass.constructor.call(this, config);
		},
		initComponent: function() {
			this.savebutton = new SaveButton({
				form:this
			});
			this.databaseTypeComboBox = new DatabaseTypeComboBox(); 
			this.items = [{
				fieldLabel:'Name',
				name:'name'
			},
			this.databaseTypeComboBox,{
				fieldLabel:'Host',
				name:'host'
			},{
				fieldLabel:'Username',
				name:'username'
			},{
				fieldLabel:'Password',
				inputType:'password',
				name:'password'
			},{
				fieldLabel:'Default Database',
				name:'defaultDatabase'
			},{
				fieldLabel:'Port',
				name:'port'
			}];
			this.buttons = [this.savebutton];
			this.savebutton.on('click', this.onSaveButtonClick, this);
			this.on('show', this.onNewConnectionFormShow, this);
			this.on('hide', this.onNewConnectionFormHide, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onNewConnectionFormShow:function(form) {
			this.getForm().findField('name').focus();
		},
		onNewConnectionFormHide:function(form) {
			this.getForm().reset();
		},
		onSaveButtonClick:function(button, e) {
			this.savebutton.setDisabled(true);
			var conn = new ui.connectionstore.recordType(this.getForm().getFieldValues());
			ui.connectionstore.add(conn);
			this.hide();
			this.savebutton.setDisabled(false);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();