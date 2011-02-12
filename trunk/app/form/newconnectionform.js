Ext.ns('monoql.form');
monoql.form.newconnectionform = function() {
	var cls = 'monoql-form-newconnectionform';
	
	var SaveButton = Ext.extend(monoql.button.button, {
		text:'Save',
		initComponent:function() {
			this.on('click', this.onSaveButtonClick, this);
			SaveButton.superclass.initComponent.call(this);
			this.addClass(cls + "-savebutton");
		},
		onSaveButtonClick:function(button, e) {
			button.form.getForm().submit();
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
		editable:false,
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
		api:{
			load:monoql.direct.Connection.get,
			submit:monoql.direct.Connection.formCreate
		},
		paramsAsHash:true,
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
				name:'defaultDatabase',
				emptyText:'[Optional]'
			},{
				fieldLabel:'Port',
				name:'port',
				emptyText:'[Optional]'
			}];
			this.buttons = [this.savebutton];
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
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();