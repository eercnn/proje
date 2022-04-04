
import React, { useState } from 'react';
import logo from './logo.svg';
import './App.css';
import Icerik from './components/Icerik';
import Ekle from './components/Ekle';



const outtest = (e) =>{
console.log(e.target.className);
 if(e.target.className==='yenikayit')
 {
  const sec = document.querySelector('.yenikayit');
  sec.style.display='none'
 }
}
const yeniekle = () =>{

    const sec = document.querySelector('.yenikayit');

  sec.style.display='flex'
}

function App() {
  return (
    <div className="App" onClick={outtest}>
      <Icerik />
      <Ekle/>
      <div className='butonicin'>
        <button onClick={yeniekle}>Yeni Ekle</button>
      </div>
    </div>
  );
}

export default App;
