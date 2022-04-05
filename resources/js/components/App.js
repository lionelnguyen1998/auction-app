import './App.css';
import React, { Fragment } from 'react';
import { Routes, Route } from 'react-router-dom';
import HomePage from './components/HomePage/HomePage';
import Contacts from './components/Contacts/Contacts';
import Footer from './components/Footer/Footer';
import Header from './components/Header/Header';
import Login from './components/Login/Login1';

function App() {
  return (
    <Fragment>
      <div className="MainDiv">
            <Header />
                <Routes>
                    <Route path="/" element={<HomePage />}/>
                    <Route path="/contacts" element={<Contacts />}/>
                    <Route path="/login" element={<Login />}/>
                </Routes>
            <Footer />
        </div>
    </Fragment>
  );
}

export default App;
