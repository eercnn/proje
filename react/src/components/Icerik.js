import { useState,useEffect } from "react";
import DataTable from "./DataTable";


export default function Icerik() {
    const [isLoaded, setIsLoaded] = useState(false);
    const [items, setItems] = useState([]);

    useEffect(() => {
        fetch("http://localhost/prj/",{
          headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        }
        })
          .then(res => res.json())
          .then((result) => {
              setIsLoaded(true);
              setItems(result);
            })
      }, [])
    
     if (!isLoaded) {
        return 'loading...';
      }else{
      return (
        <DataTable icerik={items} />
    );
  }
}
