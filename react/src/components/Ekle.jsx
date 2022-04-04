import 'react-phone-number-input/style.css'
import PhoneInput,{ isValidPhoneNumber } from 'react-phone-number-input'
import { useEffect, useState } from 'react'


export default function Ekle() {
    const [value, setValue] = useState('')
    const [fullname, setFullname] = useState('')
    const [email, setEmail] = useState('')
    const [result, setResult] = useState('')
    const islem ='ekle'
    const postla = async (data) => {
        const location = window.location.hostname;
        const settings = {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
            
        };
        try {
            const fetchResponse = await fetch(`http://localhost/prj/index.php?s=ekle`, settings);
            const data = await fetchResponse.json();
      
            return data;
        } catch (e) {
            return e;
        }    
    
    }
    
    const submitfr = (e) =>{
        let error;
        if(fullname==='' || email==='' || value==='')
        {
            error = 'Alanlar bos kalmamali'
        }else{
            if(!fullname.includes(' ')){
                error = 'Please check full name';
            }
            if(!/.+@.+\.[A-Za-z]+$/.test(email)){
                error = 'Please check email'
            }
            if(!isValidPhoneNumber(value))
             error = 'Please check phone number';
            if(!error){
             postla({fullname:fullname,phone:value,email:email,islem:islem}).then((resolve,reject)=>{
              
                setResult(resolve['r']);
             });
            
            }else
            setResult(error);
        }
        e.preventDefault();
    }
  
  return (
      
    <div class="yenikayit">
        <form action="" onSubmit={submitfr} method="post">
            <input type="text" value={fullname} onChange={(e)=>setFullname(e.target.value)} name="fullname" placeholder="Full Name"/>
            <input type="text" name="email" value={email} onChange={(e)=>setEmail(e.target.value)} placeholder="Email Adress"/>
            <PhoneInput
      placeholder="Phone Number"
      value={value}
      error={value ? (isValidPhoneNumber(value) ? undefined : 'Invalid phone number') : 'Phone number required'}
      onChange={setValue}/>
      
            <input type="hidden" name="islem" value={islem} placeholder="Full Name"/>
            <input type="submit" value="Add"/>
            {
               result
            }
        </form>
    </div>
  )
}
