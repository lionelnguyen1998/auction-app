import React, { Fragment, useState, useEffect } from 'react';

function Login(){
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [infos, setInfos] = useState([]);

    const handleSubmit = () => {
        setInfos([...infos, {
            email,
            password
        }]);
        console.log(infos)
        return infos;
    }
    useEffect(() => {
        fetch('http://localhost:8080/api/login', {
            method: 'POST',
            headers: {
                'Accept': 'application/json, text/plain,*/*',
                'Content-Type': 'application/json',
                'redirect': 'manual',
            },
            body: JSON.stringify({
                "email": email,
                "password": password,
            })
        })
        .then(function(res) {
            return res.json();
        })
    }, [])

    return (
        <Fragment>
        
                <div>
                    <label>Email</label>
                    <input
                     
                        value={email} 
                        onChange={e => setEmail(e.target.value)}
                    />
                </div>
                <div>
                    <label>password</label>
                    <input
                        type="password"
                        
                        value={password}
                        onChange={e => setPassword(e.target.value)}
                    />
                </div>
                <button onClick={handleSubmit}>Submit</button>
         
        </Fragment>
    )
}

export default Login;