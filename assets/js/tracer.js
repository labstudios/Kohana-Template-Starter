var trace = {
	
	isOn: true,

	out:function ()
    {
        if(this.options.isOn)
        {
            console.log(arguments);
        }
    },
    
    alert:function (msg)
    {
        if(this.options.isOn)
        {
            alert(msg);
        }
    }
};