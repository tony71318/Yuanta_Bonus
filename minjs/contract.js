var Web3=require("web3"),web3=new Web3;web3.setProvider(new web3.providers.HttpProvider("http://140.113.72.54:8545"));var MyContract=web3.eth.contract([{constant:!1,inputs:[{name:"newSellPrice",type:"uint256"},{name:"newBuyPrice",type:"uint256"}],name:"setPrices",outputs:[],payable:!1,type:"function"},{constant:!0,inputs:[],name:"name",outputs:[{name:"",type:"string",value:"testtoken"}],payable:!1,type:"function"},{constant:!1,inputs:[{name:"_spender",type:"address"},{name:"_value",type:"uint256"}],name:"approve",outputs:[{name:"success",type:"bool"}],payable:!1,type:"function"},{constant:!0,inputs:[],name:"totalSupply",outputs:[{name:"",type:"uint256",value:"0"}],payable:!1,type:"function"},{constant:!1,inputs:[{name:"_from",type:"address"},{name:"_to",type:"address"},{name:"_value",type:"uint256"}],name:"transferFrom",outputs:[{name:"success",type:"bool"}],payable:!1,type:"function"},{constant:!0,inputs:[],name:"decimals",outputs:[{name:"",type:"uint8",value:"5"}],payable:!1,type:"function"},{constant:!0,inputs:[],name:"sellPrice",outputs:[{name:"",type:"uint256",value:"0"}],payable:!1,type:"function"},{constant:!0,inputs:[],name:"standard",outputs:[{name:"",type:"string",value:"Token 0.1"}],payable:!1,type:"function"},{constant:!0,inputs:[{name:"",type:"address"}],name:"balanceOf",outputs:[{name:"",type:"uint256",value:"0"}],payable:!1,type:"function"},{constant:!1,inputs:[{name:"target",type:"address"},{name:"mintedAmount",type:"uint256"}],name:"mintToken",outputs:[],payable:!1,type:"function"},{constant:!0,inputs:[],name:"buyPrice",outputs:[{name:"",type:"uint256",value:"0"}],payable:!1,type:"function"},{constant:!0,inputs:[],name:"owner",outputs:[{name:"",type:"address",value:"0x6d623fe432b1e48d57b2a804cee71f1d4ee4b2a1"}],payable:!1,type:"function"},{constant:!0,inputs:[],name:"symbol",outputs:[{name:"",type:"string",value:"T"}],payable:!1,type:"function"},{constant:!1,inputs:[],name:"buy",outputs:[],payable:!0,type:"function"},{constant:!1,inputs:[{name:"_to",type:"address"},{name:"_value",type:"uint256"}],name:"transfer",outputs:[],payable:!1,type:"function"},{constant:!0,inputs:[{name:"",type:"address"}],name:"frozenAccount",outputs:[{name:"",type:"bool",value:!1}],payable:!1,type:"function"},{constant:!1,inputs:[{name:"_spender",type:"address"},{name:"_value",type:"uint256"},{name:"_extraData",type:"bytes"}],name:"approveAndCall",outputs:[{name:"success",type:"bool"}],payable:!1,type:"function"},{constant:!0,inputs:[{name:"",type:"address"},{name:"",type:"address"}],name:"allowance",outputs:[{name:"",type:"uint256",value:"0"}],payable:!1,type:"function"},{constant:!1,inputs:[{name:"amount",type:"uint256"}],name:"sell",outputs:[],payable:!1,type:"function"},{constant:!1,inputs:[{name:"target",type:"address"},{name:"freeze",type:"bool"}],name:"freezeAccount",outputs:[],payable:!1,type:"function"},{constant:!1,inputs:[{name:"newOwner",type:"address"}],name:"transferOwnership",outputs:[],payable:!1,type:"function"},{inputs:[{name:"initialSupply",type:"uint256",index:0,typeShort:"uint",bits:"256",displayName:"initial Supply",template:"elements_input_uint",value:"10000"},{name:"tokenName",type:"string",index:1,typeShort:"string",bits:"",displayName:"token Name",template:"elements_input_string",value:"testtoken"},{name:"decimalUnits",type:"uint8",index:2,typeShort:"uint",bits:"8",displayName:"decimal Units",template:"elements_input_uint",value:"5"},{name:"tokenSymbol",type:"string",index:3,typeShort:"string",bits:"",displayName:"token Symbol",template:"elements_input_string",value:"T"},{name:"centralMinter",type:"address",index:4,typeShort:"address",bits:"",displayName:"central Minter",template:"elements_input_address",value:"0x6d623fe432b1e48d57b2a804cee71f1d4ee4b2a1"}],type:"constructor"},{payable:!1,type:"fallback"},{anonymous:!1,inputs:[{indexed:!1,name:"target",type:"address"},{indexed:!1,name:"frozen",type:"bool"}],name:"FrozenFunds",type:"event"},{anonymous:!1,inputs:[{indexed:!0,name:"from",type:"address"},{indexed:!0,name:"to",type:"address"},{indexed:!1,name:"value",type:"uint256"}],name:"Transfer",type:"event"}]);myContractAddress="0x6FcFe6DE694FEDC1Bb1cE625F427C50911E8431b";var myContractInstance=MyContract.at(myContractAddress);