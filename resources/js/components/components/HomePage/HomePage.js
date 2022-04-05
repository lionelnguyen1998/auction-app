import React, { Fragment } from 'react';
import Hero from '../Hero/Hero';
import Category from '../Category/Category';
import Features from '../Features/Features';
import AuthService from "../services/auth.service";

function HomePage(){
    const currentUser = AuthService.getCurrentUser();
    console.log(currentUser)
    return (
        <Fragment>
            <Hero />
            
            <Category/>
            
            <Features />
        </Fragment>
    )
}

export default HomePage;