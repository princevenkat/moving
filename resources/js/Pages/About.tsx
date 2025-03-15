import React from 'react';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {Head} from "@inertiajs/react";

function About() {
  return (
    <AuthenticatedLayout>
      <Head title="About" />
      <div className="max-w-7xl mx-auto">
      <h1>Welcome</h1>
      </div>
    </AuthenticatedLayout>
  );
}

export default About;
