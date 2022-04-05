import React, { Fragment, useEffect, useState } from 'react';
import { yupResolver } from '@hookform/resolvers/yup';
import { useForm } from 'react-hook-form';
import * as yup from 'yup';
import { toast } from 'react-toastify';

function LoginForm(props){
    const { onSubmit } = props;
  const schema = yup.object().shape({
    email: yup
      .string()
      .required('Please enter your email')
      .email('Please enter a valid email'),

    password: yup.string().required('Please enter your password').min(6),
  });

  const form = useForm({
    defaultValues: {
      email: '',
      password: '',
    },
    resolver: yupResolver(schema),
  });

  const handleSubmit = (values) => {
    if (!onSubmit) return;
    onSubmit(values);
  };
    return (
        <Fragment>
            <form onSubmit={form.handleSubmit(handleSubmit)}>
                <div>
                    <label>Email</label>
                    <input
                        name="email"
                       form={form}
                        
                    />
                </div>
                <div>
                    <label>password</label>
                    <input
                        type="password"
                        name="password"
                        form={form}
                    />
                </div>
                <button onClick={handleSubmit}>Submit</button>
            </form>
        </Fragment>
    )
}

export default LoginForm;