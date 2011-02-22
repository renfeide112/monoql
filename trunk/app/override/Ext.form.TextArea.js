Ext.override(Ext.form.TextArea, {
	// This will add a DOM attribute to prevent spellcheck in at least Firefox...
	onRender:Ext.form.TextArea.prototype.onRender.createSequence(function() {
		this.el.dom.spellcheck = "";
	})
});