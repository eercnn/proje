import React from 'react'

export default function DataTable({icerik}) {
    const stunlar = icerik[0] && Object.keys(icerik[0])
  return (
    <table>
        <thead>
            <tr>{icerik[0] && stunlar.map((head)=><th>{head}</th>)}</tr>
        </thead>
        <tbody>
            {icerik[0] && icerik.map(s =>
            <tr key={s.id}>
                {
                    stunlar.map(stun=> <td>{s[stun]}</td>)
                }
            </tr>    
            )}
        </tbody>
    </table>
  )
}
