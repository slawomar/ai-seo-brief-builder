import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export default function Show({ brief }) {
  const [state, setState] = useState(brief);

  useEffect(() => {
    let timer = null;

    const tick = async () => {
      const res = await fetch(route('briefs.status', brief.id));
      const data = await res.json();
      setState((prev) => ({ ...prev, ...data }));

      if (data.status === 'done' || data.status === 'failed') {
        clearInterval(timer);
      }
    };

    timer = setInterval(tick, 1200);
    tick();

    return () => clearInterval(timer);
  }, [brief.id]);

  return (
    <AuthenticatedLayout>
      <Head title={brief.title} />

      <div className="max-w-5xl mx-auto p-6 space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-xl font-semibold">{brief.title}</h1>
            <div className="text-sm text-gray-600">
              status: {state.status} · progress: {state.progress}%
            </div>
          </div>
          <Link className="underline" href={route('projects.show', brief.project_id)}>Back to project</Link>
        </div>

        <div className="bg-white rounded-xl shadow p-6">
          <div className="w-full bg-gray-200 rounded h-3 overflow-hidden">
            <div className="h-3 bg-black" style={{ width: `${state.progress || 0}%` }} />
          </div>

          {state.status === 'failed' && (
            <div className="mt-4 text-red-600">
              Error: {state.error_message}
            </div>
          )}

          {state.status === 'done' && (
            <div className="mt-6">
              <h2 className="text-lg font-semibold mb-2">Result JSON</h2>
              <pre className="bg-gray-50 border rounded p-4 overflow-auto text-sm">
{JSON.stringify(state.result_json, null, 2)}
              </pre>
            </div>
          )}
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
